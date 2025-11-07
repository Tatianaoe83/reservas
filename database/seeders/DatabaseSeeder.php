<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario administrador
        User::firstOrCreate(
            ['email' => 'admin@reservas.com'],
            [
                'name' => 'Administrador',
                'email' => 'admin@reservas.com',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Usuario administrador creado:');
        $this->command->info('Email: admin@reservas.com');
        $this->command->info('Password: admin123');
        $this->command->warn('¡IMPORTANTE! Cambia la contraseña después del primer inicio de sesión.');
        
        $this->command->newLine();
        $this->command->info('Ejecutando seeders de datos...');
        
        // Ejecutar seeders en orden
        $this->call([
            ClienteSeeder::class,
            VehiculoSeeder::class,
            ReservaSeeder::class,
        ]);
        
        $this->command->newLine();
        $this->command->info('¡Todos los seeders se ejecutaron correctamente!');
    }
}
