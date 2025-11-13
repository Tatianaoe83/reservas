<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ClienteController extends Controller
{
    public function index(): View
    {
        $clientes = Cliente::with('producto')->orderBy('negocio')->get();
        return view('clientes.index', compact('clientes'));
    }

    public function create(): View
    {
        $productos = Producto::orderBy('nombre')->get();
        return view('clientes.create', compact('productos'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'negocio' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|size:10|regex:/^[0-9]+$/',
            'contacto' => 'required|string|max:255',
            'precio_venta' => 'required|numeric|min:0',
            'producto_id' => 'nullable|exists:productos,id',
        ], [
            'telefono.size' => 'El teléfono debe tener exactamente 10 dígitos.',
            'telefono.regex' => 'El teléfono solo puede contener números.',
            'producto_id.exists' => 'El producto seleccionado no es válido.',
        ]);

        Cliente::create($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado exitosamente.');
    }

    public function show(Cliente $cliente): View
    {
        $cliente->load('producto');
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente): View
    {
        $productos = Producto::orderBy('nombre')->get();
        return view('clientes.edit', compact('cliente', 'productos'));
    }

    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        $validated = $request->validate([
            'negocio' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|size:10|regex:/^[0-9]+$/',
            'contacto' => 'required|string|max:255',
            'precio_venta' => 'required|numeric|min:0',
            'producto_id' => 'nullable|exists:productos,id',
        ], [
            'telefono.size' => 'El teléfono debe tener exactamente 10 dígitos.',
            'telefono.regex' => 'El teléfono solo puede contener números.',
            'producto_id.exists' => 'El producto seleccionado no es válido.',
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
