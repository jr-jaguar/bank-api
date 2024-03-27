<?php

namespace App\DTO;

class BankBranchDTO
{
    public string $city_name;
    public string $city_slug;
    public string $lat;
    public string $lng;
    public string $address;
    public string $branch_name;
    public string $phone;
    public bool $primary;
    public string $bank_slug;
    public int $bank_id;

    public function __construct(array $data)
    {
        $this->city_name = $data['city_name'] ?? '';
        $this->city_slug = $data['city_slug'] ?? '';
        $this->lat = $data['lat'] ?? '';
        $this->lng = $data['lng'] ?? '';
        $this->address = $data['address'] ?? '';
        $this->branch_name = $data['branch_name'] ?? '';
        $this->phone = $data['phone'] ?? '';
        $this->primary = $data['primary'] ?? false;
        $this->bank_slug = $data['bank_slug'] ?? '';
        $this->bank_id = $data['bank_id'] ?? 0;
    }
}
