<?php

namespace App\Services;

use App\DTO\BankDTO;
use App\Models\Bank;
use App\Models\Currency;

class BankService
{
    public function getAllBanks()
    {
        return Bank::all();
    }

    public function getBankById($id)
    {
        return Bank::with('branches', 'exchangeRates')->findOrFail($id);
    }

    public function createBank($data)
    {
        return Bank::create($data);
    }

    public function updateBank($id, $data)
    {
        $bank = Bank::findOrFail($id);
        $bank->update($data);
        return $bank;
    }

    public function deleteBank($id)
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();
        return $bank;
    }

    public function saveBanks(array $banksDTOs): void
    {
        foreach ($banksDTOs as $bankDTO) {
            $this->saveBank($bankDTO);
        }
    }

    public function saveBank(BankDTO $bankDTO): void
    {
        $bank = Bank::firstOrNew(['slug' => $bankDTO->slug]);
        $bank->slug = $bankDTO->slug;
        $bank->title = $bankDTO->title;
        $bank->longTitle = $bankDTO->longTitle;
        $bank->ratingBank = $bankDTO->ratingBank;
        $bank->licenseNumber = $bankDTO->licenseNumber;
        $bank->licenseDate = $bankDTO->licenseDate;
        $bank->legalAddress = $bankDTO->legalAddress;
        $bank->site = $bankDTO->site;
        $bank->phone = $bankDTO->phone;
        $bank->email = $bankDTO->email;
        $bank->minfinSlug = $bankDTO->minfinSlug;
        $bank->mfoCode = $bankDTO->mfoCode;
        $bank->edrpouCode = $bankDTO->edrpouCode;
        $bank->swiftCode = $bankDTO->swiftCode;
        $bank->isActive = $bankDTO->isActive;
        $bank->save();
    }

    public function getBanksSlugAndId()
    {
        return Bank::pluck('id', 'slug')->toArray();
    }

    public function getBankIdBySlug(string $slug)
    {
        return Bank::where('slug', $slug)->pluck('id')->first();
    }
}
