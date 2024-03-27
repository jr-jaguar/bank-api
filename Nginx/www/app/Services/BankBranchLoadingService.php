<?php

namespace App\Services;

use App\Contracts\BankBranchLoadingServiceInterface;
use Illuminate\Support\Facades\Http;

class BankBranchLoadingService implements BankBranchLoadingServiceInterface
{
    private string $bankBranchConfigApiUrl;
    private string $bankBranchListApiUrl;
    private array $banksBranchResponce;

    public function __construct(private BankService $bankService)
    {
        $this->bankBranchConfigApiUrl = env('BANK_BRANCHES_API_URL');
    }


    public function loadBankBranchData(): array
    {
        $banksArray = $this->bankService->getBanksSlugAndId();

        $banksData = [];

        foreach ($banksArray as $bankSlug => $bankId) {
            $banksData = array_merge($banksData, $this->loadBankBranchDataByCode($bankSlug, $bankId));
        }

        return $banksData;
    }

    public function loadBankBranchDataByCode(string $bankSlug, int $bankId = null): array
    {
        $this->prepareBankBranchLink($bankSlug);
        $this->fetchBankBranchData();

        if (!$bankId) {
            $bankId = $this->bankService->getBankIdBySlug($bankSlug);
        }

        $bankBranchData = $this->prepareBankBranchData($this->banksBranchResponce['data'], $bankSlug, $bankId);

        if (empty($bankBranchData)) {
            throw new \Exception('Bank branch not found for code ' . $bankSlug);
        }

        return $bankBranchData;
    }

    private function fetchBankBranchData(): mixed
    {
        $response = Http::get($this->bankBranchListApiUrl);

        if ($response->ok()) {
            return $this->banksBranchResponce = $response->json();
        }

        throw new \Exception('Failed to load bank data from API');
    }

    private function prepareBankBranchLink(string $bankSlug): void
    {
        $this->bankBranchListApiUrl = $url = sprintf($this->bankBranchConfigApiUrl, $bankSlug);
        ;
    }

    private function prepareBankBranchData(array $bankBranchData, string $bankSlug, int $bankId): array
    {
        $resultArray = [];

        foreach ($bankBranchData as $item) {
            foreach ($item['data'] as $branch) {
                $resultArray[] = [
                    'city_name' => $item['name'],
                    'city_slug' => $item['slug'],
                    'lat' => $branch['lat'],
                    'lng' => $branch['lng'],
                    'address' => $branch['address'],
                    'branch_name' => $branch['branch_name'],
                    'phone' => $branch['phone'],
                    'primary' => $branch['primary'],
                    'bank_slug' => $bankSlug,
                    'bank_id' => $bankId
                ];
            }
        }

        return $resultArray;
    }

}
