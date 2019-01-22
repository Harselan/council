<?php

namespace App\Providers;

use \App\Channel;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Validator::extend( 'spamfree', 'App\Rules\SpamFree@passes' );
	    \Validator::extend( 'recaptcha', 'App\Rules\Recaptcha@passes' );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if( $this->app->isLocal() )
        {
        	$this->app->register( \Barryvdh\Debugbar\ServiceProvider::class );
        }
    }
}
