<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        "slug",
        "title",
        "longTitle",
        "ratingBank",
        "licenseNumber",
        "licenseDate",
        "legalAddress",
        "site",
        "phone",
        "email",
        "minfinSlug",
        "mfoCode",
        "edrpouCode",
        "swiftCode",
        "isActive",
    ];

    public function branches(): hasMany
    {
        return $this->hasMany(BankBranch::class);
    }
}
