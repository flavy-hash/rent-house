<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Approximate centre coordinates for each region (used to backfill existing rows).
     */
    private array $regionCoords = [
        'Arusha' => [-3.3869, 36.6830],
        'Dar es Salaam' => [-6.7924, 39.2083],
        'Dodoma' => [-6.1630, 35.7516],
        'Mwanza' => [-2.5164, 32.9175],
        'Moshi' => [-3.3349, 37.3404],
        'Zanzibar' => [-6.1659, 39.2026],
        'Mbeya' => [-8.9094, 33.4608],
        'Morogoro' => [-6.8278, 37.6591],
        'Tanga' => [-5.0689, 39.0988],
        'Iringa' => [-7.7700, 35.6900],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->decimal('latitude', 10, 7)->nullable()->after('area');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->json('photos')->nullable()->after('image');
        });

        // Give all existing (seeded) properties to the admin account.
        $admin = DB::table('users')->where('role', 'admin')->first()
            ?? DB::table('users')->orderBy('id')->first();

        if ($admin) {
            DB::table('properties')->whereNull('user_id')->update(['user_id' => $admin->id]);
        }

        // Backfill coordinates from the region centre so existing rows appear on the map.
        foreach ($this->regionCoords as $region => [$lat, $lng]) {
            DB::table('properties')
                ->where('region', $region)
                ->whereNull('latitude')
                ->update(['latitude' => $lat, 'longitude' => $lng]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['latitude', 'longitude', 'photos']);
        });
    }
};
