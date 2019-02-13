<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;
use App\Broadcasters\SyncBroadcaster;

class TwilioServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton( 'sync', function () {
            $client = new \Twilio\Rest\Client(
                env('TWILIO_ACCOUNT_SID'),
                env('TWILIO_AUTH_TOKEN')
            );

            return $client->sync->services(
                env('TWILIO_SYNC_SERVICE_SID')
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::extend( 'sync', function ( $app ) {
            return new SyncBroadcaster( $app->make( 'sync' ) );
        });
    }
}
