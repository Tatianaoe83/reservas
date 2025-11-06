<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $fillable = [
        'negocio',
        'direccion',
        'telefono',
        'contacto',
        'precio_venta',
    ];

    protected $casts = [
        'precio_venta' => 'decimal:2',
    ];

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }
}
