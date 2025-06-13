<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Usuario administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@cipolletti.gob.ar',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
            'activo' => true,
        ]);

        // Usuario administrativo
        User::create([
            'name' => 'Usuario Administrativo',
            'email' => 'administrativo@cipolletti.gob.ar',
            'role' => 'administrativo',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
            'activo' => true,
        ]);

        // Usuario inspector
        User::create([
            'name' => 'Inspector Municipal',
            'email' => 'inspector@cipolletti.gob.ar',
            'role' => 'inspector',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
            'activo' => true,
        ]);

        $this->command->info('✅ Se crearon 3 usuarios de prueba');
    }
}
