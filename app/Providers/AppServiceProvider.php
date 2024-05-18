<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Rules\ExistsOrZero;

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
        Carbon::macro('toFormattedDate', function () {
            return $this->format('Y-m-d');
        });

        Validator::extend('exists_or_zero', function ($attribute, $value, $parameters, $validator) {
            $rule = new ExistsOrZero();
            return $rule->passes($attribute, $value);
        });

    }
}
