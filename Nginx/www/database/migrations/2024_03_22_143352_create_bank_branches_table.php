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
        Schema::create('bank_branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_id');
            $table->string('city_slug');
            $table->string('city_name');
            $table->string('address');
            $table->string('branch_name');
            $table->string('phone');
            $table->string('bank_slug');
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
//            $table->decimal('lat', 10, 8)->nullable();
//            $table->decimal('lng', 11, 8)->nullable();
            $table->boolean('primary');
            $table->timestamps();

            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_branches');
    }
};
