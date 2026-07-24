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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('region');           // e.g. Arusha, Dar es Salaam
            $table->string('area')->nullable();  // e.g. Njiro, Masaki
            $table->string('type');             // House, Apartment, Room, Studio, Villa
            $table->unsignedBigInteger('price'); // monthly rent in TZS
            $table->unsignedTinyInteger('bedrooms')->default(1);
            $table->unsignedTinyInteger('bathrooms')->default(1);
            $table->text('description')->nullable();
            $table->json('amenities')->nullable();
            $table->string('image')->nullable();
            $table->string('landlord_name');
            $table->string('phone');            // for call + WhatsApp
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->index(['region', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
