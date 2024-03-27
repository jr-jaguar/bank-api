<?php

namespace App\Contracts;

interface CurrencyLoadingServiceInterface
{
    public function loadCurrencyData(): array;

    public function loadCurrencyDataByCode(string $currencyCode): array;
}
