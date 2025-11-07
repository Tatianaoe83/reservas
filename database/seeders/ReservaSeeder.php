<?php

namespace Database\Seeders;

use App\Models\Reserva;
use App\Models\Cliente;
use App\Models\Vehiculo;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ReservaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los clientes y vehículos
        $clientes = Cliente::all();
        $vehiculos = Vehiculo::all();

        if ($clientes->isEmpty() || $vehiculos->isEmpty()) {
            $this->command->error('No hay clientes o vehículos. Ejecuta primero ClienteSeeder y VehiculoSeeder.');
            return;
        }

        // Horarios disponibles (7:00 AM a 7:00 PM en intervalos de 30 minutos)
        $horarios = [];
        $hora = Carbon::parse('07:00');
        while ($hora->lte(Carbon::parse('19:00'))) {
            $horarios[] = $hora->format('H:i:s');
            $hora->addMinutes(30);
        }

        // Generar reservas para los próximos 30 días
        $reservas = [];
        $fechaInicio = Carbon::today();
        
        for ($dia = 0; $dia < 30; $dia++) {
            $fecha = $fechaInicio->copy()->addDays($dia);
            
            // No generar reservas los domingos (día 0)
            if ($fecha->dayOfWeek == 0) {
                continue;
            }

            // Generar entre 2 y 8 reservas por día
            $cantidadReservas = rand(2, 8);
            $horariosUsados = [];
            
            for ($i = 0; $i < $cantidadReservas; $i++) {
                // Seleccionar un cliente y vehículo aleatorio
                $cliente = $clientes->random();
                $vehiculo = $vehiculos->random();
                
                // Seleccionar un horario aleatorio que no esté ocupado para este vehículo
                $horariosDisponibles = array_filter($horarios, function($hora) use ($horariosUsados, $vehiculo, $fecha) {
                    $clave = $vehiculo->id . '-' . $fecha->format('Y-m-d') . '-' . $hora;
                    return !isset($horariosUsados[$clave]);
                });
                
                if (empty($horariosDisponibles)) {
                    continue;
                }
                
                $horaSeleccionada = $horariosDisponibles[array_rand($horariosDisponibles)];
                $clave = $vehiculo->id . '-' . $fecha->format('Y-m-d') . '-' . $horaSeleccionada;
                $horariosUsados[$clave] = true;
                
                // Generar cantidad aleatoria entre 10 y 100
                $cantidad = rand(10, 100);
                
                // Algunas reservas tendrán observaciones
                $observaciones = null;
                if (rand(1, 4) == 1) {
                    $observacionesVariadas = [
                        'Entregar en la parte trasera del local',
                        'Llamar 30 minutos antes de llegar',
                        'El cliente pagará en efectivo',
                        'Llevar factura',
                        'Entregar antes de las 10 AM',
                        'El contacto no estará, dejar con seguridad',
                        'Cliente requiere recibo',
                        'Entrega urgente',
                    ];
                    $observaciones = $observacionesVariadas[array_rand($observacionesVariadas)];
                }
                
                $reservas[] = [
                    'cliente_id' => $cliente->id,
                    'vehiculo_id' => $vehiculo->id,
                    'fecha' => $fecha->format('Y-m-d'),
                    'hora' => $horaSeleccionada,
                    'cantidad' => $cantidad,
                    'observaciones' => $observaciones,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insertar todas las reservas
        Reserva::insert($reservas);

        $this->command->info('Se crearon ' . count($reservas) . ' reservas para los próximos 30 días.');
    }
}
