<?php

namespace App\Services;

use App\DTO\CurrencyDTO;
use App\Models\Currency;

class CurrencyService
{
    public function getAllCurrencies()
    {
        return Currency::all();
    }

    public function getAllCurrencyCodesAndIds()
    {
        return Currency::pluck('id', 'code')->toArray();
    }

    public function getCurrencyById($id)
    {
        return Currency::findOrFail($id);
    }

    public function createCurrency($data)
    {
        return Currency::create($data);
    }

    public function updateCurrency($id, $data)
    {
        $currency = Currency::findOrFail($id);
        $currency->update($data);
        return $currency;
    }

    public function deleteCurrency($id)
    {
        $currency = Currency::findOrFail($id);
        $currency->delete();
        return $currency;
    }

    public function saveCurrencies(array $currencyDTOs): void
    {
        foreach ($currencyDTOs as $currencyDTO) {
            $this->saveCurrency($currencyDTO);
        }
    }

    public function saveCurrency(CurrencyDTO $currencyDTO): void
    {
        $currency = Currency::firstOrNew(['code' => $currencyDTO->code]);
        $currency->name = $currencyDTO->name;
        $currency->slug = $currencyDTO->slug;
        $currency->iso = $currencyDTO->iso;
        $currency->title = $currencyDTO->title;
        $currency->save();
    }
}
