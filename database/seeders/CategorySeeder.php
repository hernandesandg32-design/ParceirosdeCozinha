<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }

    Category::insert([
    ['nome' => 'Bebidas',  'emoji' => '🥤', 'slug' => 'bebidas'],
    ['nome' => 'Vegano',   'emoji' => '🥗', 'slug' => 'vegano'],
    ['nome' => 'Carnes',   'emoji' => '🥩', 'slug' => 'carnes'],
    ['nome' => 'Doces',    'emoji' => '🍰', 'slug' => 'doces'],
    ['nome' => 'Massas',   'emoji' => '🍝', 'slug' => 'massas'],
]);
}
