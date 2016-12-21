<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\BaseUser;

class ShareBaseUserWithViewsProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
         view()->share('base_user', new BaseUser());
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
