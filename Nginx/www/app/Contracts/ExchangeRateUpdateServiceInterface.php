<?php

namespace App\Contracts;

interface ExchangeRateUpdateServiceInterface
{
    public function updateExchangeRates(): array;

}
