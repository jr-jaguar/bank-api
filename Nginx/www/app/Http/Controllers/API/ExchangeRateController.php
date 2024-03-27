<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExchangeRateResource;
use App\Services\ExchangeRateService;
use Illuminate\Http\JsonResponse;

class ExchangeRateController extends Controller
{
    protected $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        $this->exchangeRateService = $exchangeRateService;
    }

    public function getExchangeRatesForCurrency($currencyCode): ExchangeRateResource
    {
        $exchangeRates = $this->exchangeRateService->getExchangeRatesForCurrencyByCode($currencyCode);
        return new ExchangeRateResource($exchangeRates);
    }

    public function getAverageExchangeRates(): JsonResponse
    {
        $exchangeRates = $this->exchangeRateService->getAverageExchangeRates();
        return response()->json($exchangeRates);
    }
}
