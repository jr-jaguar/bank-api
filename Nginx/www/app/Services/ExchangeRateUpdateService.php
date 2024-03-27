<?php

namespace App\Services;

use App\Contracts\ExchangeRateUpdateServiceInterface;
use App\Enum\ExchangeRateType;
use Illuminate\Console\Concerns\InteractsWithIO;
use Illuminate\Support\Facades\Http;

class ExchangeRateUpdateService implements ExchangeRateUpdateServiceInterface
{
    use InteractsWithIO;
    protected $baseUrl;

    public function __construct(private CurrencyService $currencyService, private BankService $bankService)
    {
        $this->baseUrl = env('EXCHANGE_RATE_API_URL');
    }

    public function updateExchangeRates(): array
    {
        $currencies = $this->currencyService->getAllCurrencyCodesAndIds();
        $banks = $this->bankService->getBanksSlugAndId();

        $ratesData = [];

        foreach ($currencies as $currencyCode => $currencyId) {
            $rates = $this->fetchExchangeRateData($currencyCode);
            foreach ($banks as $bankSlug => $bankId) {
                $filteredData = array_values(
                    array_filter(
                        $rates['data'],
                        function ($item) use ($bankSlug) {
                            return $item['slug'] === $bankSlug;
                        }
                    )
                );
                $rateData = reset($filteredData);
                if (empty($rateData)) {
                    continue;
                }
                $ratesData = array_merge($ratesData, $this->prepareRateData($rateData, $bankId, $currencyId));
            }
        }
        return $ratesData;
    }


    private function fetchExchangeRateData(string $currency): mixed
    {
        $response = Http::get($this->baseUrl . $currency. '?cpp=50');

        if ($response->ok()) {
            return $response->json();
        }

        throw new \Exception('Failed to load ExchangeRate data from API');
    }

    private function prepareRateData(array $data, int $bankId, int $currencyId): array
    {
        $resultData = [];
        if ($data['cash'] !== null) {
            $resultData[] = [
                'bank_id' => $bankId,
                'currency_id' => $currencyId,
                'type' => ExchangeRateType::CASH,
                'date' => $data['cash']['date'],
                'bid' => $data['cash']['bid'],
                'ask' => $data['cash']['ask']

            ];
        }

        if ($data['card'] !== null) {
            $resultData[] = [
                'bank_id' => $bankId,
                'currency_id' => $currencyId,
                'type' => ExchangeRateType::CARD,
                'date' => $data['cash']['date'],
                'bid' => $data['cash']['bid'],
                'ask' => $data['cash']['ask']

            ];
        }


        return $resultData;
    }
}
