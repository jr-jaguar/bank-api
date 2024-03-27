<?php

namespace App\Contracts;

interface BankLoadingServiceInterface
{
    public function loadBankData(): array;

    public function loadBankDataByCode(string $bankSlug): array;
}
