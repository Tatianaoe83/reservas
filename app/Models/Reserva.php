<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    protected $fillable = [
        'cliente_id',
        'vehiculo_id',
        'fecha',
        'hora',
        'cantidad',
        'observaciones',
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
