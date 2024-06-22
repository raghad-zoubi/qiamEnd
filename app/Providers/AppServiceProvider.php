<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;



use App\Services\FFmpegService;

class AppServiceProvider extends ServiceProvider

{
    public function register()
    {
        $this->app->singleton(FFmpegService::class, function ($app) {
            return new FFmpegService();
        });
    }

    public function boot()
    {
        //
    }


}
