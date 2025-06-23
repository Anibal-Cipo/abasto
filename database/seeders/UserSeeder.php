<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🔄 Creando usuarios del sistema...');

        // Usuario administrador principal
        User::updateOrCreate(
            ['email' => 'admin@cipolletti.gob.ar'],
            [
                'name' => 'Administrador Principal',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'activo' => true,
            ]
        );
        $this->command->info('✅ Administrador creado: admin@cipolletti.gob.ar');

        // Usuario administrativo
        User::updateOrCreate(
            ['email' => 'administrativo@cipolletti.gob.ar'],
            [
                'name' => 'Usuario Administrativo',
                'role' => 'administrativo',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'activo' => true,
            ]
        );
        $this->command->info('✅ Administrativo creado: administrativo@cipolletti.gob.ar');

        // Inspectores de prueba
        $inspectores = [
            [
                'name' => 'Inspector Municipal 1',
                'email' => 'inspector1@cipolletti.gob.ar',
            ],
            [
                'name' => 'Inspector Municipal 2',
                'email' => 'inspector2@cipolletti.gob.ar',
            ],
            [
                'name' => 'Inspector Municipal 3',
                'email' => 'inspector3@cipolletti.gob.ar',
            ]
        ];

        foreach ($inspectores as $inspector) {
            User::updateOrCreate(
                ['email' => $inspector['email']],
                [
                    'name' => $inspector['name'],
                    'role' => 'inspector',
                    'password' => Hash::make('inspector123'),
                    'email_verified_at' => now(),
                    'activo' => true,
                ]
            );
            $this->command->info("✅ Inspector creado: {$inspector['email']}");
        }

        $this->command->info('');
        $this->command->info('📋 CREDENCIALES DE ACCESO:');
        $this->command->info('====================================');
        $this->command->info('👑 ADMIN: admin@cipolletti.gob.ar / admin123');
        $this->command->info('📊 ADMINISTRATIVO: administrativo@cipolletti.gob.ar / admin123');
        $this->command->info('🔍 INSPECTORES: inspector1@cipolletti.gob.ar / inspector123');
        $this->command->info('🔍 INSPECTORES: inspector2@cipolletti.gob.ar / inspector123');
        $this->command->info('🔍 INSPECTORES: inspector3@cipolletti.gob.ar / inspector123');
        $this->command->info('====================================');
    }
}
