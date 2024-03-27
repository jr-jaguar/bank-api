<?php

namespace App\Http\Resources;

use App\Enum\ExchangeRateType;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExchangeRateResource extends ResourceCollection
{
    public function toArray($request)
    {
        $ratesByBank = [];

        foreach ($this->collection as $rate) {
            $bankId = $rate->bank_id;
            $type = $this->getTypeLabel($rate->type);

            if (!isset($ratesByBank[$bankId])) {
                $ratesByBank[$bankId] = [
                    'longTitle' => $rate->longTitle,
                    'bank_id' => $rate->bank_id,
                    'currency_id' => $rate->currency_id,
                    'currency_title' => $rate->title,
                    'currency_name' => $rate->name,
                    'currency_code' => $rate->code,
                ];
            }

            $ratesByBank[$bankId][$type] = [
                'bid' => $rate->bid,
                'ask' => $rate->ask,
                'date' => $rate->date,
            ];

        }

        return $ratesByBank;

    }

    private function getTypeLabel(int $type): string
    {
        return match ($type) {
            ExchangeRateType::CASH->value => ExchangeRateType::CASH->name,
            ExchangeRateType::CARD->value => ExchangeRateType::CARD->name,
            default => 'unknown',
        };
    }
}
