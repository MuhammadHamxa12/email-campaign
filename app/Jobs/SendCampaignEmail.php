<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Models\Customer;
use App\Models\EmailStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCampaignEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $campaign;
    protected $customer;
    protected $emailStatus;

    public function __construct(Campaign $campaign, Customer $customer, EmailStatus $emailStatus)
    {
        $this->campaign = $campaign;
        $this->customer = $customer;
        $this->emailStatus = $emailStatus;
    }

    public function handle()
    {
        try {
            Mail::raw($this->campaign->body, function ($message) {
                $message->to($this->customer->email, $this->customer->name)
                        ->subject($this->campaign->subject);
            });

            $this->emailStatus->update(['status' => 'sent']);
        } catch (\Exception $e) {
            $this->emailStatus->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);
        }
    }
}
