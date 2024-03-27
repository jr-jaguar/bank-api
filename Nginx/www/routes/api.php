<?php

use App\Http\Controllers\API\BankBranchController;
use App\Http\Controllers\API\ExchangeRateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('exchange-rates/{currencyCode}', [ExchangeRateController::class, 'getExchangeRatesForCurrency']);
    Route::get('average-rates', [ExchangeRateController::class, 'getAverageExchangeRates']);
    Route::get('bank-branches', [BankBranchController::class, 'getNearestBankBranches']);
});
