<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    public const ESTATUS_PROGRAMADO = 'programado';
    public const ESTATUS_ENTREGADO = 'entregado';
    public const ESTATUS_NO_ENTREGADO = 'no entregado';

    public const ESTATUS = [
        self::ESTATUS_PROGRAMADO => 'Programado',
        self::ESTATUS_ENTREGADO => 'Entregado',
        self::ESTATUS_NO_ENTREGADO => 'No Entregado',
    ];

    protected $fillable = [
        'cliente_id',
        'vehiculo_id',
        'fecha',
        'hora',
        'cantidad',
        'observaciones',
        'estatus',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora' => 'string', // Mantener como string para comparaciones
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class);
    }
}
