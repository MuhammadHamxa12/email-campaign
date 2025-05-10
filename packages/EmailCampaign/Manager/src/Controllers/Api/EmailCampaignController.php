<?php

namespace EmailCampaign\Manager\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use EmailCampaign\Manager\Models\Customer;
use EmailCampaign\Manager\Models\EmailLog;
use EmailCampaign\Manager\Models\EmailCampaign;
use EmailCampaign\Manager\Jobs\SendCampaignEmailJob;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmailCampaignController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'subject' => 'required|string',
                'body' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $campaign = EmailCampaign::create($validator->validated());

            return response()->json([
                'message' => 'Campaign created successfully',
                'data' => $campaign
            ], 201);

        } catch (\Throwable $e) {
            Log::error('Error creating campaign', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to create campaign'], 500);
        }
    }

    public function send(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|string',
                'days_to_expire' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $campaign = EmailCampaign::findOrFail($id);

            $query = Customer::query();

            if ($request->status) {
                $query->where('status', $request->status);
            }

            if ($request->has('days_to_expire')) {
                $query->whereBetween('plan_expiry_date', [
                    Carbon::today()->startOfDay(),
                    Carbon::today()->addDays($request->days_to_expire)->endOfDay()
                ]);

            }

            $customers = $query->get();

            foreach ($customers as $customer) {
                EmailLog::create([
                    'email_campaign_id' => $campaign->id,
                    'customer_id' => $customer->id,
                    'status' => 'queued'
                ]);
                SendCampaignEmailJob::dispatch($customer, $campaign);
            }

            return response()->json([
                'message' => 'Emails queued for filtered audience.',
                'filtered_customers' => $customers
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Campaign not found'], 404);
        } catch (\Throwable $e) {
            Log::error('Error sending campaign emails', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to queue emails'], 500);
        }
    }
}
