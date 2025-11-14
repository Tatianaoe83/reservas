<?php

namespace App\Console\Commands;

use App\Models\Reserva;
use Illuminate\Console\Command;

class MarcarReservasNoEntregadas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservas:marcar-no-entregadas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marca las reservas programadas del día actual como no entregadas al finalizar la jornada';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $limite = now();

        $actualizadas = Reserva::query()
            ->whereRaw("TIMESTAMP(fecha, hora) <= ?", [$limite->toDateTimeString()])
            ->where('estatus', Reserva::ESTATUS_PROGRAMADO)
            ->update([
                'estatus' => Reserva::ESTATUS_NO_ENTREGADO,
            ]);

        $this->info("Reservas actualizadas: {$actualizadas}");

        return Command::SUCCESS;
    }
}

