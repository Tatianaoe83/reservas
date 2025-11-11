<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    public const CATEGORIAS = [
        'Gastos de Oficina',
        'Seguros',
        'Impuestos',
        'Combustible',
        'Mantenimiento',
        'Mano de Obra',
    ];

    protected $fillable = [
        'categoria',
        'nombre',
        'fecha',
        'cantidad',
        'sueldo_semana',
        'lectura_inicial_combustible',
        'lectura_final_combustible',
        'precio_combustible',
    ];

    protected $casts = [
        'fecha' => 'date',
        'sueldo_semana' => 'decimal:2',
        'lectura_inicial_combustible' => 'decimal:2',
        'lectura_final_combustible' => 'decimal:2',
        'precio_combustible' => 'decimal:2',
    ];

    public function esCombustible(): bool
    {
        return $this->categoria === 'Combustible';
    }

    public function getLitrosConsumidosAttribute(): ?float
    {
        if (
            !$this->esCombustible()
            || is_null($this->lectura_inicial_combustible)
            || is_null($this->lectura_final_combustible)
        ) {
            return null;
        }

        return max($this->lectura_final_combustible - $this->lectura_inicial_combustible, 0);
    }

    public function getCostoCombustibleAttribute(): ?float
    {
        if (!$this->esCombustible() || is_null($this->precio_combustible)) {
            return null;
        }

        $litrosConsumidos = $this->litros_consumidos;

        return is_null($litrosConsumidos) ? null : round($litrosConsumidos * $this->precio_combustible, 2);
    }

    public function getImporteSemanalAttribute(): float
    {
        if ($this->esCombustible()) {
            return $this->costo_combustible ?? 0.0;
        }

        return round($this->sueldo_semana * $this->cantidad, 2);
    }
}
