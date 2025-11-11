<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InsumoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $insumos = Insumo::orderByDesc('created_at')->get();

        return view('insumos.index', compact('insumos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('insumos.create', [
            'categorias' => Insumo::CATEGORIAS,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateInsumo($request);

        Insumo::create($validated);

        return redirect()
            ->route('insumos.index')
            ->with('success', 'Insumo registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Insumo $insumo): View
    {
        return view('insumos.show', compact('insumo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Insumo $insumo): View
    {
        return view('insumos.edit', [
            'insumo' => $insumo,
            'categorias' => Insumo::CATEGORIAS,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Insumo $insumo): RedirectResponse
    {
        $validated = $this->validateInsumo($request);

        $insumo->update($validated);

        return redirect()
            ->route('insumos.index')
            ->with('success', 'Insumo actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Insumo $insumo): RedirectResponse
    {
        $insumo->delete();

        return redirect()
            ->route('insumos.index')
            ->with('success', 'Insumo eliminado correctamente.');
    }

    /**
     * Valida los datos de creación/edición de un insumo.
     */
    private function validateInsumo(Request $request): array
    {
        $categorias = implode(',', array_map(fn ($categoria) => str_replace(',', '\,', $categoria), Insumo::CATEGORIAS));

        $esCombustible = $request->input('categoria') === 'Combustible';

        $rules = [
            'categoria' => 'required|in:' . $categorias,
            'nombre' => 'required|string|max:255',
            'fecha' => 'required|date',
            'cantidad' => $esCombustible ? 'nullable|integer|min:1' : 'required|integer|min:1',
            'sueldo_semana' => $esCombustible ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'lectura_inicial_combustible' => $esCombustible ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
            'lectura_final_combustible' => $esCombustible ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
            'precio_combustible' => $esCombustible ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
        ];

        if ($esCombustible) {
            $rules['lectura_final_combustible'] .= '|gte:lectura_inicial_combustible';
        }

        $validated = $request->validate($rules, [
            'categoria.in' => 'La categoría seleccionada no es válida.',
            'cantidad.min' => 'La cantidad debe ser al menos 1.',
            'sueldo_semana.min' => 'El sueldo semanal debe ser igual o mayor a 0.',
            'lectura_final_combustible.gte' => 'La lectura final debe ser mayor o igual a la lectura inicial.',
        ]);

        if ($esCombustible) {
            $validated['cantidad'] = $validated['cantidad'] ?? 1;
            $validated['sueldo_semana'] = $validated['sueldo_semana'] ?? 0;
        }

        return $validated;
    }
}
