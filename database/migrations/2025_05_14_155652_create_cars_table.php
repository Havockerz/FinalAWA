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
        Schema::create('cars', function (Blueprint $table) {
        $table->id();
        $table->string('brand'); // Make of the car (e.g., Toyota)
        $table->string('model'); // Model of the car (e.g., Corolla)
        $table->year('year'); // Manufacturing year
        $table->decimal('price_per_day', 8, 2); // Price per day for rental
        $table->string('license_plate')->unique(); // License plate number
        $table->text('description')->nullable(); // Optional car description
        $table->string('status')->default('available'); // Status (e.g., available, rented)
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
