<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ClienteController extends Controller
{
    public function index(): View
    {
        $clientes = Cliente::orderBy('negocio')->get();
        return view('clientes.index', compact('clientes'));
    }

    public function create(): View
    {
        return view('clientes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'negocio' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|size:10|regex:/^[0-9]+$/',
            'contacto' => 'required|string|max:255',
            'precio_venta' => 'required|numeric|min:0',
        ], [
            'telefono.size' => 'El teléfono debe tener exactamente 10 dígitos.',
            'telefono.regex' => 'El teléfono solo puede contener números.',
        ]);

        Cliente::create($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado exitosamente.');
    }

    public function show(Cliente $cliente): View
    {
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente): View
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        $validated = $request->validate([
            'negocio' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|size:10|regex:/^[0-9]+$/',
            'contacto' => 'required|string|max:255',
            'precio_venta' => 'required|numeric|min:0',
        ], [
            'telefono.size' => 'El teléfono debe tener exactamente 10 dígitos.',
            'telefono.regex' => 'El teléfono solo puede contener números.',
        ]);

        $cliente->update($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado exitosamente.');
    }
}
