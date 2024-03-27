<?php

namespace App\Console\Commands;

use App\Contracts\ExchangeRateUpdateServiceInterface;
use App\DTO\ExchangeRateDTO;
use App\Enum\ExchangeRateType;
use App\Services\ExchangeRateService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class UpdateExchangeRatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-exchange-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update exchange rates';


    public function __construct(
        private ExchangeRateUpdateServiceInterface $exchangeRateUpdateService,
        private ExchangeRateService $exchangeRateService,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $exchangeRates = $this->exchangeRateUpdateService->updateExchangeRates();

        $validatedData = $this->validateData($exchangeRates);

        $exchangeRateDTOs = [];

        foreach ($validatedData as $bank) {

            $exchangeRateDTOs[] = $this->convertToDTO($bank);
        }

        $this->exchangeRateService->uploadExchangeRates($exchangeRateDTOs);


        $this->info('Exchange rates updated successfully.');
    }

    private function validateData($data): array
    {
        $validator = Validator::make(
            $data,
            [
            '*.bank_id' => 'required|integer',
            '*.currency_id' => 'required|integer',
            '*.type' => [new Enum(ExchangeRateType::class)],
            '*.date' => 'required|string',
            '*.ask' => 'required|string',
            '*.bid' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
        return $data;
    }

    private function convertToDTO($data): ExchangeRateDTO
    {
        return new ExchangeRateDTO(
            [
                'bank_id' => $data['bank_id'] ?? '',
                'currency_id' => $data['currency_id'] ?? '',
                'type' => $data['type'] ?? '',
                'date' => $data['date'] ?? '',
                'bid' => $data['bid'] ?? 0.0,
                'ask' => $data['ask'] ?? 0.0,
            ]
        );
    }
}
