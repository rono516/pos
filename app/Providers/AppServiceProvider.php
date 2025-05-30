<?php
namespace App\Providers;

use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);

        // if (! $this->app->runningInConsole()) {
        //     $settings = Setting::all('key', 'value')
        //         ->keyBy('key')
        //         ->transform(function ($setting) {
        //             return $setting->value;
        //         })
        //         ->toArray();
        //     config([
        //        'settings' => $settings
        //     ]);

        //     config(['app.name' => config('settings.app_name')]);
        // }
        if (! $this->app->runningInConsole()) {
            $settings = Setting::first(); // Assuming only one settings row exists

            if ($settings) {
                config([
                    'settings' => $settings->toArray(),
                    'app.name' => $settings->app_name,
                ]);
            }
        }

        Paginator::useBootstrap();
    }
}
