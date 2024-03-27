<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('longTitle');
            $table->float('ratingBank', 2);
            $table->string('licenseNumber');
            $table->string('licenseDate');
            $table->string('legalAddress');
            $table->string('site');
            $table->string('phone');
            $table->string('email');
            $table->string('minfinSlug');
            $table->string('edrpouCode');
            $table->string('swiftCode');
            $table->string('mfoCode');
            $table->integer('isActive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
