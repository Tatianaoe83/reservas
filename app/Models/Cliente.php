<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cliente extends Model
{
    protected $fillable = [
        'negocio',
        'direccion',
        'telefono',
        'contacto',
        'precio_venta',
        'producto_id',
    ];

    protected $casts = [
        'precio_venta' => 'decimal:2',
    ];

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }
}
