<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Customer;
use App\Models\EmailStatus;
use App\Jobs\SendCampaignEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Exception;

class CampaignController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'subject' => 'required|string',
                'body' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $campaign = Campaign::create($request->only('title', 'subject', 'body'));

            return response()->json([
                'status' => 'success',
                'message' => 'Campaign created successfully!',
                'campaign' => $campaign
            ], 201);
        } catch (Exception $e) {
            Log::error('Campaign Store Error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred while creating the campaign.'
            ], 500);
        }
    }

    public function filterAudience(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|string',
                'days_to_expiry' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = Customer::query();

            if ($request->status) {
                $query->where('status', $request->status);
            }

            if ($request->days_to_expiry) {
                $query->whereBetween('plan_expiry_date', [now(), now()->addDays($request->days_to_expiry)]);
            }

            $customers = $query->get();

            return response()->json([
                'status' => 'success',
                'message' => $customers->isEmpty() ? 'No customers found.' : 'Audience filtered successfully!',
                'customers' => $customers,
            ], 200);
        } catch (Exception $e) {
            Log::error('Filter Audience Error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred while filtering the audience.'
            ], 500);
        }
    }

    public function sendEmails(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'campaign_id' => 'required|exists:campaigns,id',
                'customer_ids' => 'required|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $campaign = Campaign::findOrFail($request->campaign_id);

            $customers = Customer::whereIn('id', $request->customer_ids)->get();

            if ($customers->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No valid customers found for the provided IDs.'
                ], 404);
            }

            foreach ($customers as $customer) {
                $status = EmailStatus::create([
                    'campaign_id' => $campaign->id,
                    'customer_id' => $customer->id,
                    'status' => 'pending',
                ]);

                SendCampaignEmail::dispatch($campaign, $customer, $status);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Emails have been queued successfully!'
            ], 200);
        } catch (Exception $e) {
            Log::error('Send Emails Error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred while sending emails.'
            ], 500);
        }
    }
}
