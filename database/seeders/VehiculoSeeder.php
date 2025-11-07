<?php

namespace Database\Seeders;

use App\Models\Vehiculo;
use Illuminate\Database\Seeder;

class VehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehiculos = [
            ['nombre' => 'Camión Grande'],
            ['nombre' => 'Camión Mediano'],
            ['nombre' => 'Camión Pequeño'],
            ['nombre' => 'Pickup'],
            ['nombre' => 'Camioneta'],
            ['nombre' => 'Furgón'],
            ['nombre' => 'Refrigerado'],
            ['nombre' => 'Carro pequeño'],
        ];

        foreach ($vehiculos as $vehiculo) {
            Vehiculo::create($vehiculo);
        }

        $this->command->info('Se crearon ' . count($vehiculos) . ' vehículos.');
    }
}
