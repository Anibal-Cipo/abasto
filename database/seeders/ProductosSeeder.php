<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductosSeeder extends Seeder
{
    public function run()
    {
        $productos = [
            // CARNES - Productos MIXTOS (cantidad/peso)
            [
                'nombre' => '1/2 res bovina c/h',
                'categoria' => 'CARNES',
                'tipo_medicion' => 'MIXTO',
                'unidad_primaria' => 'medias res',
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 7,
                'requiere_temperatura' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Cortes bovinos c/h',
                'categoria' => 'CARNES',
                'tipo_medicion' => 'MIXTO',
                'unidad_primaria' => 'cortes',
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 7,
                'requiere_temperatura' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Corte bovina-asados',
                'categoria' => 'CARNES',
                'tipo_medicion' => 'PESO',
                'unidad_primaria' => null,
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 7,
                'requiere_temperatura' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Carne porcina (varios)',
                'categoria' => 'CARNES',
                'tipo_medicion' => 'PESO',
                'unidad_primaria' => null,
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 7,
                'requiere_temperatura' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Pollo y otras aves',
                'categoria' => 'CARNES',
                'tipo_medicion' => 'MIXTO',
                'unidad_primaria' => 'unidades',
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 5,
                'requiere_temperatura' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Carnes caprina (chivo)',
                'categoria' => 'CARNES',
                'tipo_medicion' => 'PESO',
                'unidad_primaria' => null,
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 7,
                'requiere_temperatura' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Carne ovina (cordero-capón)',
                'categoria' => 'CARNES',
                'tipo_medicion' => 'PESO',
                'unidad_primaria' => null,
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 7,
                'requiere_temperatura' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Hamburguesas',
                'categoria' => 'CARNES',
                'tipo_medicion' => 'MIXTO',
                'unidad_primaria' => 'unidades',
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 3,
                'requiere_temperatura' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Menudencias',
                'categoria' => 'CARNES',
                'tipo_medicion' => 'PESO',
                'unidad_primaria' => null,
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 2,
                'requiere_temperatura' => true,
                'activo' => true
            ],

            // LÁCTEOS
            [
                'nombre' => 'Chacinados',
                'categoria' => 'FIAMBRES',
                'tipo_medicion' => 'PESO',
                'unidad_primaria' => null,
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 30,
                'requiere_temperatura' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Tripas',
                'categoria' => 'FIAMBRES',
                'tipo_medicion' => 'PESO',
                'unidad_primaria' => null,
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 15,
                'requiere_temperatura' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Grasa bovina',
                'categoria' => 'CARNES',
                'tipo_medicion' => 'PESO',
                'unidad_primaria' => null,
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 7,
                'requiere_temperatura' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Pescados y mariscos',
                'categoria' => 'PESCADOS',
                'tipo_medicion' => 'PESO',
                'unidad_primaria' => null,
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 2,
                'requiere_temperatura' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Supercongelados',
                'categoria' => 'CONGELADOS',
                'tipo_medicion' => 'PESO',
                'unidad_primaria' => null,
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 90,
                'requiere_temperatura' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Margarina',
                'categoria' => 'LACTEOS',
                'tipo_medicion' => 'PESO',
                'unidad_primaria' => null,
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 60,
                'requiere_temperatura' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Helados-postres h.',
                'categoria' => 'LACTEOS',
                'tipo_medicion' => 'MIXTO',
                'unidad_primaria' => 'unidades',
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 180,
                'requiere_temperatura' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Fiambres varios',
                'categoria' => 'FIAMBRES',
                'tipo_medicion' => 'PESO',
                'unidad_primaria' => null,
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 30,
                'requiere_temperatura' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Lácteos',
                'categoria' => 'LACTEOS',
                'tipo_medicion' => 'PESO',
                'unidad_primaria' => null,
                'unidad_secundaria' => 'litros',
                'dias_vencimiento' => 7,
                'requiere_temperatura' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Yogurt (potes)',
                'categoria' => 'LACTEOS',
                'tipo_medicion' => 'MIXTO',
                'unidad_primaria' => 'potes',
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 15,
                'requiere_temperatura' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Leche (unidades)',
                'categoria' => 'LACTEOS',
                'tipo_medicion' => 'MIXTO',
                'unidad_primaria' => 'unidades',
                'unidad_secundaria' => 'litros',
                'dias_vencimiento' => 5,
                'requiere_temperatura' => true,
                'activo' => true
            ],

            // PRODUCTOS SECOS/PANADERÍA
            [
                'nombre' => 'Harina',
                'categoria' => 'PANADERIA',
                'tipo_medicion' => 'PESO',
                'unidad_primaria' => null,
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 365,
                'requiere_temperatura' => false,
                'activo' => true
            ],
            [
                'nombre' => 'Hielo',
                'categoria' => 'OTROS',
                'tipo_medicion' => 'PESO',
                'unidad_primaria' => null,
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 1,
                'requiere_temperatura' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Pastas frescas',
                'categoria' => 'PANADERIA',
                'tipo_medicion' => 'PESO',
                'unidad_primaria' => null,
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 3,
                'requiere_temperatura' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Panificación',
                'categoria' => 'PANADERIA',
                'tipo_medicion' => 'MIXTO',
                'unidad_primaria' => 'unidades',
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 2,
                'requiere_temperatura' => false,
                'activo' => true
            ],
            [
                'nombre' => 'Productos de almacén',
                'categoria' => 'ALMACEN',
                'tipo_medicion' => 'PESO',
                'unidad_primaria' => null,
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 365,
                'requiere_temperatura' => false,
                'activo' => true
            ],
            [
                'nombre' => 'Golosinas',
                'categoria' => 'ALMACEN',
                'tipo_medicion' => 'PESO',
                'unidad_primaria' => null,
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 180,
                'requiere_temperatura' => false,
                'activo' => true
            ],

            // FRUTAS Y VERDURAS
            [
                'nombre' => 'Frutas y verduras',
                'categoria' => 'FRUTAS_VERDURAS',
                'tipo_medicion' => 'PESO',
                'unidad_primaria' => null,
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 7,
                'requiere_temperatura' => false,
                'activo' => true
            ],

            // BEBIDAS
            [
                'nombre' => 'Bebidas alcohólicas',
                'categoria' => 'BEBIDAS',
                'tipo_medicion' => 'MIXTO',
                'unidad_primaria' => 'botellas',
                'unidad_secundaria' => 'litros',
                'dias_vencimiento' => 1825, // 5 años
                'requiere_temperatura' => false,
                'activo' => true
            ],
            [
                'nombre' => 'Bebidas analcohólicas',
                'categoria' => 'BEBIDAS',
                'tipo_medicion' => 'MIXTO',
                'unidad_primaria' => 'botellas',
                'unidad_secundaria' => 'litros',
                'dias_vencimiento' => 365,
                'requiere_temperatura' => false,
                'activo' => true
            ],
            [
                'nombre' => 'Huevos (docenas)',
                'categoria' => 'OTROS',
                'tipo_medicion' => 'MIXTO',
                'unidad_primaria' => 'docenas',
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 30,
                'requiere_temperatura' => true,
                'activo' => true
            ],

            // OTROS
            [
                'nombre' => 'Otros',
                'categoria' => 'OTROS',
                'tipo_medicion' => 'PESO',
                'unidad_primaria' => null,
                'unidad_secundaria' => 'kg',
                'dias_vencimiento' => 30,
                'requiere_temperatura' => false,
                'activo' => true
            ]
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }

        $this->command->info('✅ Se crearon ' . count($productos) . ' productos exitosamente');
    }
}
