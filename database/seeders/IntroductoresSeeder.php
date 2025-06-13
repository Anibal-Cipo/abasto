<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Introductor;

class IntroductoresSeeder extends Seeder
{
    public function run()
    {
        $introductores = [
            [
                'razon_social' => 'Frigorífico San Martín S.A.',
                'cuit' => '30123456789',
                'direccion' => 'Ruta Nacional 22 Km 1200, Cipolletti',
                'telefono' => '0299-4771234',
                'email' => 'contacto@frigosanmartin.com.ar',
                'habilitacion_municipal' => 'HAB-2024-001',
                'activo' => true
            ],
            [
                'razon_social' => 'Distribuidora Norte Patagónico',
                'cuit' => '27987654321',
                'direccion' => 'Av. Argentina 1500, Neuquén',
                'telefono' => '0299-4425678',
                'email' => 'ventas@distrinorte.com.ar',
                'habilitacion_municipal' => 'HAB-2024-002',
                'activo' => true
            ],
            [
                'razon_social' => 'Carnicería El Buen Corte',
                'cuit' => '20345678901',
                'direccion' => 'España 234, General Roca',
                'telefono' => '0298-4431122',
                'email' => 'info@elbuencorte.com.ar',
                'habilitacion_municipal' => 'HAB-2024-003',
                'activo' => true
            ],
            [
                'razon_social' => 'Lácteos Valle Verde SRL',
                'cuit' => '30567890123',
                'direccion' => 'Ruta Provincial 7 Km 5, Allen',
                'telefono' => '0298-4562345',
                'email' => 'administracion@valleverde.com.ar',
                'habilitacion_municipal' => 'HAB-2024-004',
                'activo' => true
            ],
            [
                'razon_social' => 'Frutas y Verduras del Alto Valle',
                'cuit' => '27234567890',
                'direccion' => 'Belgrano 567, Cipolletti',
                'telefono' => '0299-4776789',
                'email' => 'pedidos@frutasaltovalle.com.ar',
                'habilitacion_municipal' => 'HAB-2024-005',
                'activo' => true
            ]
        ];

        foreach ($introductores as $introductor) {
            Introductor::create($introductor);
        }

        $this->command->info('✅ Se crearon ' . count($introductores) . ' introductores de prueba');
    }
}
