<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Customer;
use App\Models\EmailStatus;
use App\Jobs\SendCampaignEmail;
use Illuminate\Http\Request;

class CampaignWebController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::all();
        return view('campaigns.index', compact('campaigns'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'subject' => 'required',
            'body' => 'required',
        ]);

        Campaign::create($request->only('title', 'subject', 'body'));
        return redirect()->back()->with('success', 'Campaign created successfully!');
    }

    public function filter(Request $request)
    {
        $request->validate([
            'status' => 'required',
            'days_to_expiry' => 'required',
        ]);

        $query = Customer::query();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->days_to_expiry) {
            $query->whereBetween('plan_expiry_date', [now(), now()->addDays($request->days_to_expiry)]);
        }

        $customers = $query->get();
        $campaigns = Campaign::all();

        return view('campaigns.index', compact('customers', 'campaigns'))->with([
            'success' => 'Audience filtered successfully!',
            'campaign_id' => $request->campaign_id,
        ]);
    }

    public function sendEmails(Request $request)
    {
        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'customer_ids' => 'required|array',
        ]);

        $campaign = Campaign::findOrFail($request->campaign_id);

        if (!$campaign) {
            return redirect()->back()->with('error', 'Invalid campaign selected.');
        }

        $customers = Customer::whereIn('id', $request->customer_ids)->get();

        foreach ($customers as $customer) {
            $status = EmailStatus::create([
                'campaign_id' => $campaign->id,
                'customer_id' => $customer->id,
                'status' => 'pending',
            ]);

            SendCampaignEmail::dispatch($campaign, $customer, $status);
        }

        return redirect()->back()->with('success', 'Emails queued successfully!');
    }
}
