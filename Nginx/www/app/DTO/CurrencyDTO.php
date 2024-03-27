<?php

namespace App\DTO;

class CurrencyDTO
{
    public function __construct(
        public string $code,
        public string $name,
        public string $slug,
        public int $iso,
        public string $title
    ) {
    }
}
