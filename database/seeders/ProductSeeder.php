<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Aurora Smartwatch',
                'slug' => 'aurora-smartwatch',
                'description' => 'A sleek smartwatch with all-day activity tracking and vibrant health insights.',
                'price' => 129.99,
                'image_url' => 'https://placehold.co/600x600/png?text=Aurora+Smartwatch',
                'featured' => true,
                'category' => 'Electronics',
                'stock' => 40,
            ],
            [
                'name' => 'Nimbus Noise-Canceling Headphones',
                'slug' => 'nimbus-noise-canceling-headphones',
                'description' => 'Comfort-fit over-ear headphones with deep bass and ambient sound control.',
                'price' => 189.50,
                'image_url' => 'https://placehold.co/600x600/png?text=Nimbus+Headphones',
                'featured' => true,
                'category' => 'Electronics',
                'stock' => 25,
            ],
            [
                'name' => 'Prism Wireless Charger',
                'slug' => 'prism-wireless-charger',
                'description' => 'Fast, tidy wireless charging with a non-slip magnetic design.',
                'price' => 34.90,
                'image_url' => 'https://placehold.co/600x600/png?text=Prism+Charger',
                'featured' => false,
                'category' => 'Electronics',
                'stock' => 60,
            ],
            [
                'name' => 'Evergreen Desk Lamp',
                'slug' => 'evergreen-desk-lamp',
                'description' => 'Warm-to-cool dimming lamp designed for focused work and cozy nights.',
                'price' => 49.00,
                'image_url' => 'https://placehold.co/600x600/png?text=Evergreen+Lamp',
                'featured' => true,
                'category' => 'Home',
                'stock' => 30,
            ],
            [
                'name' => 'Atlas Insulated Water Bottle',
                'slug' => 'atlas-insulated-water-bottle',
                'description' => 'Keeps drinks cold for 24 hours and hot for 12, with a leak-proof lid.',
                'price' => 22.75,
                'image_url' => 'https://placehold.co/600x600/png?text=Atlas+Bottle',
                'featured' => false,
                'category' => 'Lifestyle',
                'stock' => 90,
            ],
            [
                'name' => 'Voyager Mechanical Keyboard',
                'slug' => 'voyager-mechanical-keyboard',
                'description' => 'Responsive mechanical switches with customizable lighting profiles.',
                'price' => 109.99,
                'image_url' => 'https://placehold.co/600x600/png?text=Voyager+Keyboard',
                'featured' => true,
                'category' => 'Computers',
                'stock' => 18,
            ],
            [
                'name' => 'Solstice Air Purifier',
                'slug' => 'solstice-air-purifier',
                'description' => 'Quiet room purification with a multi-stage filter system.',
                'price' => 219.00,
                'image_url' => 'https://placehold.co/600x600/png?text=Solstice+Purifier',
                'featured' => false,
                'category' => 'Home',
                'stock' => 12,
            ],
            [
                'name' => 'Crescent Ceramic Coffee Mug Set',
                'slug' => 'crescent-ceramic-coffee-mug-set',
                'description' => 'A premium mug set with a comfortable handle and smooth glaze.',
                'price' => 27.99,
                'image_url' => 'https://placehold.co/600x600/png?text=Crescent+Mug+Set',
                'featured' => false,
                'category' => 'Kitchen',
                'stock' => 45,
            ],
            [
                'name' => 'Auric Leather Wallet',
                'slug' => 'auric-leather-wallet',
                'description' => 'Genuine leather wallet with card slots, cash compartment, and RFID lining.',
                'price' => 39.95,
                'image_url' => 'https://placehold.co/600x600/png?text=Auric+Wallet',
                'featured' => true,
                'category' => 'Fashion',
                'stock' => 28,
            ],
            [
                'name' => 'Boreal Travel Backpack 30L',
                'slug' => 'boreal-travel-backpack-30l',
                'description' => 'Lightweight 30L backpack with organized compartments for every journey.',
                'price' => 74.50,
                'image_url' => 'https://placehold.co/600x600/png?text=Boreal+Backpack',
                'featured' => false,
                'category' => 'Travel',
                'stock' => 22,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => $product['slug']],
                $product
            );
        }
    }
}

