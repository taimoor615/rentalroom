<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Listing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create test users
        $host1 = User::create([
            'name' => 'John Host',
            'email' => 'host@example.com',
            'password' => Hash::make('password'),
            'role' => 'host',
            'phone' => '+1234567890',
            'bio' => 'Experienced host with multiple properties.',
        ]);

        $host2 = User::create([
            'name' => 'Sarah Host',
            'email' => 'sarah@example.com',
            'password' => Hash::make('password'),
            'role' => 'host',
            'phone' => '+0987654321',
            'bio' => 'Love hosting travelers from around the world.',
        ]);

        $guest = User::create([
            'name' => 'Jane Guest',
            'email' => 'guest@example.com',
            'password' => Hash::make('password'),
            'role' => 'guest',
            'phone' => '+1122334455',
            'bio' => 'Travel enthusiast and digital nomad.',
        ]);

        // Create sample listings
        $listings = [
            [
                'title' => 'Cozy Downtown Apartment',
                'description' => 'A beautiful apartment in the heart of the city with modern amenities and great views.',
                'price' => 120,
                'address' => '123 Main Street',
                'city' => 'New York',
                'country' => 'United States',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'max_guests' => 4,
                'amenities' => ['WiFi', 'Kitchen', 'Air Conditioning', 'TV'],
                'host_id' => $host1->id,
            ],
            [
                'title' => 'Beachfront Villa',
                'description' => 'Stunning oceanfront property with private beach access and infinity pool.',
                'price' => 450,
                'address' => '456 Ocean Drive',
                'city' => 'Miami',
                'country' => 'United States',
                'latitude' => 25.7617,
                'longitude' => -80.1918,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'max_guests' => 8,
                'amenities' => ['WiFi', 'Pool', 'Kitchen', 'Parking', 'Hot Tub'],
                'host_id' => $host1->id,
            ],
            [
                'title' => 'Mountain Cabin Retreat',
                'description' => 'Peaceful cabin surrounded by nature, perfect for a weekend getaway.',
                'price' => 85,
                'address' => '789 Forest Lane',
                'city' => 'Aspen',
                'country' => 'United States',
                'latitude' => 39.1911,
                'longitude' => -106.8175,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'max_guests' => 6,
                'amenities' => ['WiFi', 'Kitchen', 'Heating', 'Parking'],
                'host_id' => $host2->id,
            ],
            [
                'title' => 'Modern Loft in Arts District',
                'description' => 'Stylish industrial loft in trendy neighborhood with exposed brick and high ceilings.',
                'price' => 180,
                'address' => '321 Gallery Street',
                'city' => 'Los Angeles',
                'country' => 'United States',
                'latitude' => 34.0522,
                'longitude' => -118.2437,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'max_guests' => 2,
                'amenities' => ['WiFi', 'Kitchen', 'Air Conditioning', 'Gym'],
                'host_id' => $host2->id,
            ],
        ];

        foreach ($listings as $listingData) {
            Listing::create($listingData);
        }

        // Create additional random listings
        Listing::factory(20)->create([
            'host_id' => $host1->id,
        ]);

        Listing::factory(15)->create([
            'host_id' => $host2->id,
        ]);
    }
}
