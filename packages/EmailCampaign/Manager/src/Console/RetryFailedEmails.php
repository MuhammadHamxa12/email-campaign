<?php

namespace EmailCampaign\Manager\Console;

use Illuminate\Console\Command;
use EmailCampaign\Manager\Models\EmailLog;
use EmailCampaign\Manager\Jobs\SendCampaignEmailJob;

class RetryFailedEmails extends Command
{
    protected $signature = 'campaign:retry-failed';
    protected $description = 'Retry failed/bounced emails (not exceeding max retry count)';

    public function handle()
    {
        $this->info("Dispatching retries for failed/bounced emails...");

        EmailLog::with(['customer', 'campaign'])
            ->whereIn('status', ['failed', 'bounced'])
            ->where('retry_count', '<', 3)
            ->chunkById(200, function ($logs) {
                foreach ($logs as $log) {
                    if ($log->customer && $log->campaign) {
                        SendCampaignEmailJob::dispatch($log->customer, $log->campaign);
                        $log->update(['status' => 'queued']);
                    }
                }
            });

        $this->info("Retry dispatch completed.");
    }
}
