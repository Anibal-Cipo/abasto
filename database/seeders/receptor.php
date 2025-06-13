<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class receptor extends Seeder
{
    public function run()
    {
        // Actualizar introductores existentes para que sean 'ambos'
        Introductor::query()->update(['tipo' => 'ambos']);

        // Agregar algunos receptores específicos
        $receptores = [
            [
                'razon_social' => 'Mercado Central Cipolletti',
                'cuit' => '30712345678',
                'direccion' => 'Av. del Trabajo 1200, Cipolletti',
                'telefono' => '0299-4771500',
                'email' => 'mercado@cipolletti.gob.ar',
                'habilitacion_municipal' => 'REC-2024-001',
                'tipo' => 'receptor',
                'activo' => true
            ],
            [
                'razon_social' => 'Supermercados del Norte',
                'cuit' => '33445566778',
                'direccion' => 'Fernández Oro 567, Cipolletti',
                'telefono' => '0299-4425566',
                'email' => 'compras@supernorte.com.ar',
                'habilitacion_municipal' => 'REC-2024-002',
                'tipo' => 'receptor',
                'activo' => true
            ]
        ];

        foreach ($receptores as $receptor) {
            Introductor::create($receptor);
        }
    }
}
