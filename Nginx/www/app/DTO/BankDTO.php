<?php

namespace App\DTO;

class BankDTO
{
    public string $slug;
    public string $title;
    public string $longTitle;
    public float $ratingBank;
    public string $licenseNumber;
    public string $licenseDate;
    public string $legalAddress;
    public string $site;
    public string $phone;
    public string $email;
    public string $minfinSlug;
    public string $mfoCode;
    public string $edrpouCode;
    public string $swiftCode;
    public int $isActive;

    public function __construct(array $data)
    {
        $this->slug = $data['slug'] ?? '';
        $this->title = $data['title'] ?? '';
        $this->longTitle = $data['longTitle'] ?? '';
        $this->ratingBank = $data['ratingBank'] ?? '';
        $this->licenseNumber = $data['licenseNumber'] ?? '';
        $this->licenseDate = $data['licenseDate'] ?? '';
        $this->legalAddress = $data['legalAddress'] ?? '';
        $this->site = $data['site'] ?? '';
        $this->phone = $data['phone'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->minfinSlug = $data['minfinSlug'] ?? '';
        $this->mfoCode = $data['mfoCode'] ?? '';
        $this->edrpouCode = $data['edrpouCode'] ?? '';
        $this->swiftCode = $data['swiftCode'] ?? '';
        $this->isActive = $data['isActive'] ?? 1;
    }
}
