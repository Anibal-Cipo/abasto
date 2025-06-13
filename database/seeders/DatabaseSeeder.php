<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            ProductosSeeder::class,
            // IntroductoresSeeder::class, // Opcional para datos de prueba
        ]);
    }
}
