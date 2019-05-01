<?php

namespace Liliom\Zoho\Campaigns;

use Illuminate\Support\ServiceProvider;


class CampaignsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {

    }


    /**
     * Register the application services.
     *
     * @return void
     */

    public function register()
    {
        $this->app->singleton('campaigns', function () {
            return new CampaignsManager;
        });
    }

}
