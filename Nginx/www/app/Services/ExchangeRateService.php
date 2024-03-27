<?php

namespace App\Services;

use App\DTO\ExchangeRateDTO;
use App\Models\ExchangeRate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ExchangeRateService
{
    public function getAllExchangeRates()
    {
        return ExchangeRate::all();
    }

    public function getExchangeRateById($id)
    {
        return ExchangeRate::findOrFail($id);
    }

    public function createExchangeRate($data)
    {
        return ExchangeRate::create($data);
    }

    public function updateExchangeRate($id, $data)
    {
        $exchangeRate = ExchangeRate::findOrFail($id);
        $exchangeRate->update($data);
        return $exchangeRate;
    }

    public function deleteExchangeRate($id)
    {
        $exchangeRate = ExchangeRate::findOrFail($id);
        $exchangeRate->delete();
        return $exchangeRate;
    }

    public function uploadExchangeRates(array $exchangeRates): void
    {
        foreach ($exchangeRates as $exchangeRate) {
            $this->storeExchangeRateFromDTO($exchangeRate);
        }
    }

    private function storeExchangeRateFromDTO(ExchangeRateDTO $exchangeRateDTO): void
    {
        $exchangeRate = ExchangeRate::create(
            [
                'bank_id' => $exchangeRateDTO->bankId,
                'currency_id' => $exchangeRateDTO->currencyId,
                'type' => $exchangeRateDTO->type,
                'bid' => $exchangeRateDTO->bid,
                'ask' => $exchangeRateDTO->ask,
                'date' => $exchangeRateDTO->date,
            ]
        );
        $exchangeRate->save();
    }

    public function getExchangeRatesForCurrency($currencyId)
    {
        return ExchangeRate::where('currency_id', $currencyId)->get();
    }

    public function getExchangeRatesForCurrencyByCode($currencyCode): Collection
    {
        return DB::table('exchange_rates')
            ->join('banks', 'banks.id', '=', 'exchange_rates.bank_id')
            ->join('currencies', 'currencies.id', '=', 'exchange_rates.currency_id')
            ->where('currencies.code', $currencyCode)
            ->orderBy('exchange_rates.created_at', 'desc')
            ->get();
    }

    public function getAverageExchangeRates(): array
    {

        $latestCurrencyRates = DB::table('exchange_rates')
            ->select('currency_id', DB::raw('MAX(created_at) AS max_created_at'))
            ->groupBy('currency_id');

        $result = DB::table('exchange_rates as er')
            ->select(
                DB::raw('AVG(bid) as avg_bid'),
                DB::raw('AVG(ask) as avg_ask'),
                'er.currency_id',
                'c.code as currency_code'
            )
            ->joinSub(
                $latestCurrencyRates,
                'lcr',
                function ($join) {
                    $join->on('er.currency_id', '=', 'lcr.currency_id')
                        ->on('er.created_at', '=', 'lcr.max_created_at');
                }
            )
            ->join('currencies as c', 'er.currency_id', '=', 'c.id')
            ->groupBy('er.currency_id')
            ->get()->toArray();

        return $result;
    }
}
