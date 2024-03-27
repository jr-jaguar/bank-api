<?php

namespace App\Services;

use App\Contracts\CurrencyLoadingServiceInterface;
use Illuminate\Support\Facades\Http;

class CurrencyLoadingService implements CurrencyLoadingServiceInterface
{
    private string $currencyListApiUrl;

    private array $currencyResponce;

    public function __construct()
    {
        $this->currencyListApiUrl = env('CURRENCY_LIST_API_URL');
    }


    public function loadCurrencyData(): array
    {
        $defaultCurrencyCodes = ['usd', 'eur', 'gbp', 'chf', 'pln'];
        $currenciesData = [];
        foreach ($defaultCurrencyCodes as $currencyCode) {
            $currenciesData = array_merge($currenciesData, $this->loadCurrencyDataByCode(strtoupper($currencyCode)));
        }

        return $currenciesData;
    }

    public function loadCurrencyDataByCode(string $currencyCode): array
    {
        if(!$this->currencyResponce) {
            $this->currencyResponce = $this->fetchCurrencyData();
        }

        $currencyData = array_filter(
            $this->currencyResponce['list'],
            function ($data) use ($currencyCode) {
                return $data['code'] === strtoupper($currencyCode);
            }
        );

        if (empty($currencyData)) {
            throw new \Exception('Currency not found for code ' . $currencyCode);
        }
        return $currencyData;
    }

    private function fetchCurrencyData(): array
    {
        $response = Http::get($this->currencyListApiUrl);

        if ($response->ok()) {
            return $response->json();
        }

        throw new \Exception('Failed to load currency data from API');
    }
}
