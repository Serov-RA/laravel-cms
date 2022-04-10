<?php

namespace App\Providers;

use DateTime;
use Illuminate\Support\Facades\Blade;
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
        Blade::directive('datetime', function ($expression) {
            return "<?php echo empty($expression) ? '' : (new Datetime($expression))->format('d.m.Y H:i:s'); ?>";
        });

        Blade::directive('date', function ($expression) {
            return "<?php echo empty($expression) ? '' : (new Datetime($expression))->format('d.m.Y'); ?>";
        });
    }
}
