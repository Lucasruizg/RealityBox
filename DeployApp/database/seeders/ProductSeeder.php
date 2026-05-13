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
                'name' => 'RealityBox Gamer Pro',
                'description' => 'Caja gaming premium con periféricos, accesorios para setup y premios legendarios de alto valor.',
                'price' => 49.99,
                'image' => 'gamer-pro.png',
                'category' => 'gaming',
                'rating' => 4.9,
                'reviews' => 128,
                'is_popular' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'RealityBox Streamer',
                'description' => 'Accesorios para creadores de contenido, directos, escritorio y estética de setup.',
                'price' => 34.99,
                'image' => 'creator.png',
                'category' => 'gaming',
                'rating' => 4.7,
                'reviews' => 86,
                'is_popular' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'RealityBox Fitness',
                'description' => 'Premios para entrenar mejor: botellas, accesorios, smartbands y packs premium.',
                'price' => 24.99,
                'image' => 'fitness.png',
                'category' => 'fitness',
                'rating' => 4.6,
                'reviews' => 74,
                'is_popular' => false,
                'is_featured' => true,
            ],
            [
                'name' => 'RealityBox Sneakers',
                'description' => 'Moda urbana, accesorios sneaker y posibilidad de zapatillas de edición especial.',
                'price' => 39.99,
                'image' => 'sneakers.png',
                'category' => 'moda',
                'rating' => 4.8,
                'reviews' => 112,
                'is_popular' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'RealityBox Pokémon',
                'description' => 'Sobres, cartas, accesorios y premios de colección para fans de Pokémon.',
                'price' => 29.99,
                'image' => 'pokemon.png',
                'category' => 'franquicia',
                'rating' => 4.9,
                'reviews' => 154,
                'is_popular' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'RealityBox Magic',
                'description' => 'Caja para TCG con sobres, accesorios, playmats y cartas especiales.',
                'price' => 27.99,
                'image' => 'magic.png',
                'category' => 'cards',
                'rating' => 4.7,
                'reviews' => 93,
                'is_popular' => false,
                'is_featured' => true,
            ],
            [
                'name' => 'RealityBox Tech',
                'description' => 'Gadgets, accesorios útiles, powerbanks y premios tecnológicos premium.',
                'price' => 44.99,
                'image' => 'tech.png',
                'category' => 'tech',
                'rating' => 4.8,
                'reviews' => 101,
                'is_popular' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'RealityBox Booster',
                'description' => 'Caja centrada en sobres, bundles y accesorios de cartas coleccionables.',
                'price' => 14.99,
                'image' => 'booster.png',
                'category' => 'cards',
                'rating' => 4.5,
                'reviews' => 67,
                'is_popular' => false,
                'is_featured' => false,
            ],
            [
                'name' => 'RealityBox Spin',
                'description' => 'Caja lowcost rápida para abrir online con premios pequeños y sorpresas premium.',
                'price' => 4.99,
                'image' => 'spin.png',
                'category' => 'lowcost',
                'rating' => 4.4,
                'reviews' => 58,
                'is_popular' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'RealityBox Streetwear',
                'description' => 'Ropa, accesorios urbanos y premios de moda con estética streetwear.',
                'price' => 32.99,
                'image' => 'streetwear.png',
                'category' => 'moda',
                'rating' => 4.7,
                'reviews' => 89,
                'is_popular' => false,
                'is_featured' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                $product
            );
        }
    }
}
