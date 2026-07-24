<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // A few hand-picked featured listings for the landing page.
        Property::factory()->count(6)->featured()->create();

        // The rest of the catalogue.
        Property::factory()->count(30)->create();
    }
}
