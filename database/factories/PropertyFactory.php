<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Property>
 */
class PropertyFactory extends Factory
{
    protected $model = Property::class;

    /**
     * Areas keyed by region so seeded data feels local.
     */
    protected array $areas = [
        'Arusha' => ['Njiro', 'Sakina', 'Kijenge', 'Sekei', 'Themi', 'Usa River'],
        'Dar es Salaam' => ['Masaki', 'Mikocheni', 'Kinondoni', 'Mbezi Beach', 'Sinza', 'Tegeta'],
        'Dodoma' => ['Area C', 'Kikuyu', 'Nkuhungu', 'Chang\'ombe'],
        'Mwanza' => ['Ilemela', 'Nyakato', 'Buzuruga', 'Isamilo'],
        'Moshi' => ['Shanty Town', 'Kiboriloni', 'Pasua', 'Majengo'],
        'Zanzibar' => ['Stone Town', 'Nungwi', 'Kiwengwa', 'Mbweni'],
        'Mbeya' => ['Uyole', 'Iyunga', 'Forest'],
        'Morogoro' => ['Kihonda', 'Mazimbu', 'Boma'],
        'Tanga' => ['Ngamiani', 'Chumbageni', 'Mzingani'],
        'Iringa' => ['Gangilonga', 'Mkwawa', 'Kihesa'],
    ];

    protected array $images = [
        'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=1200&q=70',
        'https://images.unsplash.com/photo-1568605114967-8130f3a36994?auto=format&fit=crop&w=1200&q=70',
        'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=70',
        'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1200&q=70',
        'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=70',
        'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=70',
        'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=70',
        'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=1200&q=70',
    ];

    protected array $amenityPool = [
        'Parking', 'Water tank', 'Fenced compound', 'Security guard',
        'Backup generator', 'Tiled floors', 'Modern kitchen', 'Balcony',
        'Master en-suite', 'Garden', 'Solar water heater', 'DSTV ready',
    ];

    /**
     * Region centre coordinates (a little jitter is added per listing).
     */
    protected array $regionCoords = [
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

    public function definition(): array
    {
        $region = fake()->randomElement(Property::REGIONS);
        $area = fake()->randomElement($this->areas[$region]);
        [$lat, $lng] = $this->regionCoords[$region];
        $type = fake()->randomElement(Property::TYPES);
        $bedrooms = $type === 'Room' || $type === 'Studio'
            ? 1
            : fake()->numberBetween(1, 5);

        // Realistic monthly rent bands in TZS by type.
        $price = match ($type) {
            'Room' => fake()->numberBetween(60, 150) * 1000,
            'Studio' => fake()->numberBetween(150, 350) * 1000,
            'Apartment' => fake()->numberBetween(300, 900) * 1000,
            'House' => fake()->numberBetween(400, 1500) * 1000,
            'Villa' => fake()->numberBetween(1200, 4000) * 1000,
        };

        $title = "{$type} in {$area}, {$region}";

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.Str::lower(Str::random(4)),
            'region' => $region,
            'area' => $area,
            'latitude' => round($lat + fake()->randomFloat(4, -0.05, 0.05), 7),
            'longitude' => round($lng + fake()->randomFloat(4, -0.05, 0.05), 7),
            'type' => $type,
            'price' => $price,
            'bedrooms' => $bedrooms,
            'bathrooms' => fake()->numberBetween(1, max(1, $bedrooms)),
            'description' => "A well-maintained {$type} located in {$area}, {$region}. "
                .'Close to shops, public transport and schools. '
                .fake()->sentence(12),
            'amenities' => fake()->randomElements($this->amenityPool, fake()->numberBetween(3, 6)),
            'image' => fake()->randomElement($this->images),
            'landlord_name' => fake()->name(),
            'phone' => '0'.fake()->randomElement(['65', '68', '74', '75', '76', '78'])
                .fake()->numerify('#######'),
            'is_featured' => false,
            'is_available' => true,
        ];
    }

    public function featured(): static
    {
        return $this->state(fn () => ['is_featured' => true]);
    }
}
