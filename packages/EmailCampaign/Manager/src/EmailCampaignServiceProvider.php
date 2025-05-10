<?php

namespace EmailCampaign\Manager;

use Illuminate\Support\ServiceProvider;

class EmailCampaignServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'emailcampaign');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \EmailCampaign\Manager\Console\RetryFailedEmails::class,
            ]);
        }
    }

    public function register()
    {
        //
    }
}
