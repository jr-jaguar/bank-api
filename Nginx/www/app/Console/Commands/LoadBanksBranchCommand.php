<?php

namespace App\Console\Commands;

use App\Contracts\BankBranchLoadingServiceInterface;
use App\DTO\BankBranchDTO;
use App\Services\BankBranchService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class LoadBanksBranchCommand extends Command
{
    protected $signature = 'banks-branch:load {bankSlug?}';

    protected $description = 'Load bank branch data from the external API';


    public function __construct(
        private BankBranchLoadingServiceInterface $bankBranchLoadingService,
        private BankBranchService $bankBranchService
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $bankSlug = $this->argument('bankSlug');

        if ($bankSlug) {
            try {
                $data = $this->bankBranchLoadingService->loadBankBranchDataByCode($bankSlug);

                $validatedData = $this->validateData($data);

                $banksBranchDTOs = [];

                foreach ($validatedData as $bankBranch) {
                    $banksBranchDTOs[] = $this->convertToDTO($bankBranch);
                }
                $this->bankBranchService->saveBanksBranch($banksBranchDTOs);

                $this->info("Bank data loaded successfully.");
            } catch (\Exception $e) {
                $this->error("Failed to load bank data: " . $e->getMessage());
            }
        } else {
            try {
                $data = $this->bankBranchLoadingService->loadBankBranchData();

                $validatedData = $this->validateData($data);

                $banksBranchDTOs = [];

                foreach ($validatedData as $bankBranch) {
                    $banksBranchDTOs[] = $this->convertToDTO($bankBranch);
                }

                $this->bankBranchService->saveBanksBranch($banksBranchDTOs);

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
            '*.city_name' => 'required|string|max:50',
            '*.city_slug' => 'required|string|max:50',
            '*.lat' => 'nullable|string|max:255',
            '*.lng' => 'nullable|string|max:255',
            '*.address' => 'required|string|max:255',
            '*.branch_name' => 'required|string|max:50',
            '*.phone' => 'required|string|max:255',
            '*.primary' => 'required|boolean',
            '*.bank_slug' => 'required|string|max:255',
            '*.bank_id' => 'required|integer',
            ]
        );

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
        return $data;
    }

    private function convertToDTO($data): BankBranchDTO
    {
        return new BankBranchDTO(
            [
                'city_name' => $data['city_name'] ?? '',
                'city_slug' => $data['city_slug'] ?? '',
                'lat' => $data['lat'] ?? '',
                'lng' => $data['lng'] ?? '',
                'address' => $data['address'] ?? '',
                'branch_name' => $data['branch_name'] ?? '',
                'phone' => $data['phone'] ?? '',
                'primary' => $data['primary'] ?? '',
                'bank_slug' => $data['bank_slug'] ?? '',
                'bank_id' => $data['bank_id'] ?? '',
            ]
        );
    }
}
