<?php

namespace App\Services;

use App\Contracts\BankLoadingServiceInterface;
use Illuminate\Support\Facades\Http;

class BankLoadingService implements BankLoadingServiceInterface
{
    private string $bankListApiUrl;

    private array $banksResponce;

    public function __construct()
    {
        $this->bankListApiUrl = env('BANKS_LIST_API_URL');
        $this->banksResponce = $this->fetchBankData();
    }


    public function loadBankData(): array
    {
        $banksCount = 5;

        $banksData = [];

        for ($i = 0; $i < $banksCount; $i++) {
            $banksData[] = $this->banksResponce['responseData'][array_rand($this->banksResponce['responseData'])];
        }

        return $banksData;
    }

    public function loadBankDataByCode(string $bankSlug): array
    {
        $bankData = array_filter(
            $this->banksResponce['responseData'],
            function ($data) use ($bankSlug) {
                return $data['slug'] === $bankSlug;
            }
        );
        if (empty($bankData)) {
            throw new \Exception('Bank not found for code ' . $bankSlug);
        }

        return $bankData;
    }

    private function fetchBankData(): array
    {
        $response = Http::get($this->bankListApiUrl);

        if ($response->ok()) {
            return $response->json();
        }

        throw new \Exception('Failed to load bank data from API');
    }
}
