<?php

namespace App\Console\Commands;

use App\Contracts\BankLoadingServiceInterface;
use App\DTO\BankDTO;
use App\Services\BankService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class LoadBanksCommand extends Command
{
    protected $signature = 'banks:load {bankSlug?}';

    protected $description = 'Load bank data from the external API';


    public function __construct(
        private BankLoadingServiceInterface $bankLoadingService,
        private BankService $bankService
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $bankSlug = $this->argument('bankSlug');

        if ($bankSlug) {
            try {
                $data = $this->bankLoadingService->loadBankDataByCode($bankSlug);
                $validatedData = $this->validateData($data);
                $bankDTO = $this->convertToDTO(array_shift($validatedData));
                $this->bankService->saveBank($bankDTO);

                $this->info("Bank data loaded successfully.");
            } catch (\Exception $e) {
                $this->error("Failed to load bank data: " . $e->getMessage());
            }
        } else {
            try {
                $data = $this->bankLoadingService->loadBankData();
                $validatedData = $this->validateData($data);

                $banksDTOs = [];

                foreach ($validatedData as $bank) {

                    $banksDTOs[] = $this->convertToDTO($bank);
                }

                $this->bankService->saveBanks($banksDTOs);

                $this->info("Bank data loaded successfully.");
            } catch (\Exception $e) {
                $this->error("Failed to load bank data: " . $e->getMessage());
            }
        }
    }

    private function validateData($data): array
    {
        $validator = Validator::make(
            $data,
            [
            '*.slug' => 'required|string|max:50',
            '*.title' => 'required|string|max:255',
            '*.longTitle' => 'nullable|string|max:255',
            '*.ratingBank' => 'nullable|numeric',
            '*.licenseNumber' => 'nullable|string|max:255',
            '*.licenseDate' => 'nullable|string|max:50',
            '*.legalAddress' => 'nullable|string|max:255',
            '*.site' => 'nullable|string|max:255',
            '*.phone' => 'nullable|string|max:255',
            '*.email' => 'nullable|email|max:255',
            '*.minfinSlug' => 'nullable|string|max:50',
            '*.mfoCode' => 'nullable|string|max:10',
            '*.edrpouCode' => 'nullable|string|max:20',
            '*.swiftCode' => 'nullable|string|max:20',
            '*.isActive' => 'nullable|int'
            ]
        );

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
        return $data;
    }

    private function convertToDTO($data): BankDTO
    {
        return new BankDTO(
            [
                'slug' => $data['slug'] ?? '',
                'title' => $data['title'] ?? '',
                'longTitle' => $data['longTitle'] ?? '',
                'ratingBank' => $data['ratingBank'] ?? 0.0,
                'licenseNumber' => $data['licenseNumber'] ?? '',
                'licenseDate' => $data['licenseDate'] ?? '',
                'legalAddress' => $data['legalAddress'] ?? '',
                'site' => $data['site'] ?? '',
                'phone' => $data['phone'] ?? '',
                'email' => $data['email'] ?? '',
                'minfinSlug' => $data['minfinSlug'] ?? '',
                'mfoCode' => $data['mfoCode'] ?? '',
                'edrpouCode' => $data['edrpouCode'] ?? '',
                'swiftCode' => $data['swiftCode'] ?? '',
                'isActive' => $data['isActive'] ?? 1
            ]
        );
    }
}
