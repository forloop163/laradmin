<?php

namespace App\Providers;

use App\Business\ModelsObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 模型回调
        $observer = new ModelsObserver();
        $observer->callback();

        if (config('app.debug', false)) {
            // 输出sql日志
            $observer->printSql();
        }

        // 注册telescope
        $this->app->register(TelescopeServiceProvider::class);
    }
}
