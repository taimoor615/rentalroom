<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listing>
 */
class ListingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraphs(3, true),
            'price' => $this->faker->numberBetween(50, 500),
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'country' => $this->faker->country,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'bedrooms' => $this->faker->numberBetween(1, 5),
            'bathrooms' => $this->faker->numberBetween(1, 3),
            'max_guests' => $this->faker->numberBetween(1, 10),
            'amenities' => $this->faker->randomElements([
                'WiFi', 'Kitchen', 'Parking', 'Pool', 'Gym', 'Air Conditioning',
                'Heating', 'Washer', 'Dryer', 'TV', 'Hot Tub'
            ], $this->faker->numberBetween(2, 6)),
            'host_id' => User::factory(),
            'is_active' => true,
        ];
    }
}
