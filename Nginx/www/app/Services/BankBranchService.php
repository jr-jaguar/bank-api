<?php

namespace App\Services;

use App\DTO\BankBranchDTO;
use App\Models\BankBranch;

class BankBranchService
{
    public function getAllBankBranches()
    {
        return BankBranch::all();
    }

    public function getBankBranchById($id)
    {
        return BankBranch::findOrFail($id);
    }

    public function createBankBranch($data)
    {
        return BankBranch::create($data);
    }

    public function updateBankBranch($id, $data)
    {
        $branch = BankBranch::findOrFail($id);
        $branch->update($data);
        return $branch;
    }

    public function deleteBankBranch($id)
    {
        $branch = BankBranch::findOrFail($id);
        $branch->delete();
        return $branch;
    }

    public function saveBanksBranch(array $banksBranchDTOs): void
    {
        foreach ($banksBranchDTOs as $bankBranchDTO) {
            $this->saveBankBranch($bankBranchDTO);
        }
    }

    public function saveBankBranch(BankBranchDTO $bankBranchDTO): void
    {
        $bankBranch = BankBranch::firstOrNew(['branch_name' => $bankBranchDTO->branch_name]);
        $bankBranch->city_slug = $bankBranchDTO->city_slug;
        $bankBranch->city_name = $bankBranchDTO->city_name;
        $bankBranch->lat = $bankBranchDTO->lat;
        $bankBranch->lng = $bankBranchDTO->lng;
        $bankBranch->address = $bankBranchDTO->address;
        $bankBranch->branch_name = $bankBranchDTO->branch_name;
        $bankBranch->phone = $bankBranchDTO->phone;
        $bankBranch->primary = $bankBranchDTO->primary;
        $bankBranch->bank_slug = $bankBranchDTO->bank_slug;
        $bankBranch->bank_id = $bankBranchDTO->bank_id;
        $bankBranch->save();
    }
}
