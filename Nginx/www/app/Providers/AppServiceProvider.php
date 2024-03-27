<?php

namespace App\Providers;

use App\Contracts\BankBranchLoadingServiceInterface;
use App\Contracts\BankLoadingServiceInterface;
use App\Contracts\CurrencyLoadingServiceInterface;
use App\Contracts\ExchangeRateUpdateServiceInterface;
use App\Services\BankBranchLoadingService;
use App\Services\BankLoadingService;
use App\Services\CurrencyLoadingService;
use App\Services\ExchangeRateUpdateService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    public $bindings = [
        CurrencyLoadingServiceInterface::class => CurrencyLoadingService::class,
        BankLoadingServiceInterface::class => BankLoadingService::class,
        BankBranchLoadingServiceInterface::class => BankBranchLoadingService::class,
        ExchangeRateUpdateServiceInterface::class => ExchangeRateUpdateService::class,
    ];
}
