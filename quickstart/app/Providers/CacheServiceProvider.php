<?php

namespace App\Providers;

use Cache;
use Illuminate\Support\ServiceProvider;
use App\Extensions\MongoStore;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Cache::extend('mongo',function($app){
            return Cache::repository(new MongoStore);
        });
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
