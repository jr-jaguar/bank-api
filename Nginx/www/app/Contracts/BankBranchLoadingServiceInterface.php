<?php

namespace App\Contracts;

interface BankBranchLoadingServiceInterface
{
    public function loadBankBranchData(): array;

    public function loadBankBranchDataByCode(string $bankSlug): array;
}
