<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create 
                            {--email=admin@reservas.com : Email del administrador}
                            {--password=admin123 : Contraseña del administrador}
                            {--name=Administrador : Nombre del administrador}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear un usuario administrador para el sistema de reservas';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->option('email');
        $password = $this->option('password');
        $name = $this->option('name');

        // Verificar si el usuario ya existe
        if (User::where('email', $email)->exists()) {
            $this->error("El usuario con email {$email} ya existe.");
            
            if (!$this->confirm('¿Deseas actualizar la contraseña de este usuario?')) {
                return Command::FAILURE;
            }

            $user = User::where('email', $email)->first();
            $user->update([
                'password' => Hash::make($password),
                'name' => $name,
            ]);

            $this->info("Usuario actualizado exitosamente.");
            $this->line("Email: {$email}");
            $this->line("Contraseña: {$password}");
            return Command::SUCCESS;
        }

        // Crear nuevo usuario
        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        $this->info("Usuario administrador creado exitosamente.");
        $this->line("Email: {$email}");
        $this->line("Contraseña: {$password}");
        $this->warn('¡IMPORTANTE! Cambia la contraseña después del primer inicio de sesión.');

        return Command::SUCCESS;
    }
}
