<?php

namespace App\Providers;

use App\Services\TicketValidatorService;
use App\Services\TicketValidationStrategies\SpotNumberValidation;
use App\Services\TicketValidationStrategies\AvailabilityValidation;
use App\Services\TicketValidationStrategies\VehicleTypeValidation;
use App\Services\TicketValidationStrategies\SizeMatchValidation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TicketValidatorService::class, function ($app) {
            return new TicketValidatorService([
                $app->make(SpotNumberValidation::class),
                $app->make(AvailabilityValidation::class),
                $app->make(VehicleTypeValidation::class),
                $app->make(SizeMatchValidation::class),
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
