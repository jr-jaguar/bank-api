<?php

namespace App\Console\Commands;

use App\Contracts\CurrencyLoadingServiceInterface;
use App\DTO\CurrencyDTO;
use App\Services\CurrencyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class LoadCurrenciesCommand extends Command
{
    protected $signature = 'currencies:load {currency?}';

    protected $description = 'Load currency data from the external API';


    public function __construct(
        private CurrencyLoadingServiceInterface $currencyLoadingService,
        private CurrencyService $currencyService
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $currency = $this->argument('currency');

        if ($currency) {
            try {
                $data = $this->currencyLoadingService->loadCurrencyDataByCode($currency);

                $validatedData = $this->validateData($data);

                $currencyDTO = $this->convertToDTO(array_shift($validatedData));

                $this->currencyService->saveCurrency($currencyDTO);

                $this->info("Currency data loaded successfully.");
            } catch (\Exception $e) {
                $this->error("Failed to load currency data: " . $e->getMessage());
            }
        } else {
            try {
                $data = $this->currencyLoadingService->loadCurrencyData();

                $validatedData = $this->validateData($data);

                $currencyDTOs = [];

                foreach ($validatedData as $currency) {

                    $currencyDTOs[] = $this->convertToDTO($currency);
                }

                $this->currencyService->saveCurrencies($currencyDTOs);

                $this->info("Currency data loaded successfully.");
            } catch (\Exception $e) {
                $this->error("Failed to load currency data: " . $e->getMessage());
            }
        }
    }

    private function validateData($data): array
    {
        $validator = Validator::make(
            $data,
            [
            '*.code' => 'required|string|max:3',
            '*.name' => 'required|string|max:255',
            '*.slug' => 'required|string|max:3',
            '*.iso' => 'required|integer|nullable',
            '*.title' => 'required|string|max:3',
            ]
        );

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        return $data;
    }

    private function convertToDTO($data): CurrencyDTO
    {
        return new CurrencyDTO(
            $data['code'],
            $data['name'],
            $data['slug'],
            $data['iso'],
            $data['title']
        );
    }
}
