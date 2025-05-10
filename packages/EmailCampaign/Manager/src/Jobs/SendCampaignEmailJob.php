<?php

namespace EmailCampaign\Manager\Jobs;

use EmailCampaign\Manager\Mail\CampaignMail;
use EmailCampaign\Manager\Models\EmailLog;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Blade;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class SendCampaignEmailJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels, Dispatchable;

    public function __construct(public $customer, public $campaign) {}

    public function handle()
    {
        $log = EmailLog::where('email_campaign_id', $this->campaign->id)
            ->where('customer_id', $this->customer->id)
            ->first();

        if (!$log || $log->status === 'sent') return;
        if ($log->retry_count >= 3) return;

        try {
            // Render the Blade string
            $compiledHtml = Blade::render($this->campaign->body, [
                'name' => $this->customer->name,
                'email' => $this->customer->email,
                'plan_expiry_date' => $this->customer->plan_expiry_date,
            ]);

            Mail::to($this->customer->email)
                ->send(new CampaignMail($this->campaign->subject, $compiledHtml));

            $log->update([
                'status' => 'sent',
                'error' => null
            ]);
        } catch (\Throwable $e) {
            $log->increment('retry_count');
            $log->update([
                'status' => 'failed',
                'error' => $e->getMessage()
            ]);
        }
    }
}
