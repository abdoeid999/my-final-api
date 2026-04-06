<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $response = \Illuminate\Support\Facades\Http::get('https://fakestoreapi.com/products');

        if ($response->successful()) {
            $products = $response->json();

            foreach ($products as $item) {
                Product::updateOrCreate(
                    ['slug' => \Illuminate\Support\Str::slug($item['title'])],
                    [
                        'name' => $item['title'],
                        'description' => $item['description'],
                        'price' => $item['price'],
                        'image_url' => $item['image'],
                        'category' => $item['category'],
                        'stock' => rand(10, 100),
                        'featured' => $item['rating']['rate'] > 4,
                    ]
                );
            }
        }
    }
}

