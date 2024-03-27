<?php

namespace App\DTO;

use App\Enum\ExchangeRateType;

class ExchangeRateDTO
{
    public int $bankId;
    public int $currencyId;
    public ExchangeRateType $type;
    public float $bid;
    public float $ask;
    public string $date;

    public function __construct(
        array $data
    ) {
        $this->bankId = $data['bank_id'];
        $this->currencyId = $data['currency_id'];
        $this->type = $data['type'];
        $this->bid = $data['bid'];
        $this->ask = $data['ask'];
        $this->date = $data['date'];
    }
}
