<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Cliente;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class ReservaController extends Controller
{
    public function index(): View
    {
        $reservas = Reserva::with(['cliente', 'vehiculo'])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();
        
        return view('reservas.index', compact('reservas'));
    }

    public function calendario(): View
    {
        $reservas = Reserva::with(['cliente', 'vehiculo'])->get();
        return view('reservas.calendario', compact('reservas'));
    }

    public function create(): View
    {
        $clientes = Cliente::orderBy('negocio')->get();
        $vehiculos = Vehiculo::orderBy('nombre')->get();
        
        // Generar horarios disponibles (7:00 AM a 7:00 PM en intervalos de 30 minutos)
        $horarios = $this->generarHorarios();
        
        return view('reservas.create', compact('clientes', 'vehiculos', 'horarios'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required|date_format:H:i',
            'cantidad' => 'required|integer|min:1',
            'observaciones' => 'nullable|string',
        ]);

        // Validar que el horario esté dentro del rango permitido (7:00 AM - 7:00 PM)
        $hora = Carbon::parse($validated['hora']);
        $horaInicio = Carbon::parse('07:00');
        $horaFin = Carbon::parse('19:00');
        
        if ($hora->lt($horaInicio) || $hora->gt($horaFin)) {
            return back()->withErrors(['hora' => 'El horario debe estar entre 7:00 AM y 7:00 PM.'])->withInput();
        }

        // Validar que el horario esté en intervalos de 30 minutos
        $minutos = $hora->minute;
        if ($minutos != 0 && $minutos != 30) {
            return back()->withErrors(['hora' => 'El horario debe estar en intervalos de 30 minutos (ej: 7:00, 7:30, 8:00, etc.)'])->withInput();
        }

        // Validar disponibilidad (mismo vehículo, misma fecha y hora)
        $existeReserva = Reserva::where('vehiculo_id', $validated['vehiculo_id'])
            ->where('fecha', $validated['fecha'])
            ->where('hora', $validated['hora'])
            ->exists();

        if ($existeReserva) {
            return back()->withErrors(['hora' => 'Ya existe una reserva para este vehículo en la fecha y hora seleccionada.'])->withInput();
        }

        Reserva::create($validated);

        return redirect()->route('reservas.index')
            ->with('success', 'Reserva creada exitosamente.');
    }

    public function show(Reserva $reserva): View
    {
        $reserva->load(['cliente', 'vehiculo']);
        return view('reservas.show', compact('reserva'));
    }

    public function edit(Reserva $reserva): View
    {
        $clientes = Cliente::orderBy('negocio')->get();
        $vehiculos = Vehiculo::orderBy('nombre')->get();
        $horarios = $this->generarHorarios();
        
        return view('reservas.edit', compact('reserva', 'clientes', 'vehiculos', 'horarios'));
    }

    public function update(Request $request, Reserva $reserva): RedirectResponse
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'cantidad' => 'required|integer|min:1',
            'observaciones' => 'nullable|string',
        ]);

        // Validar que el horario esté dentro del rango permitido
        $hora = Carbon::parse($validated['hora']);
        $horaInicio = Carbon::parse('07:00');
        $horaFin = Carbon::parse('19:00');
        
        if ($hora->lt($horaInicio) || $hora->gt($horaFin)) {
            return back()->withErrors(['hora' => 'El horario debe estar entre 7:00 AM y 7:00 PM.'])->withInput();
        }

        // Validar que el horario esté en intervalos de 30 minutos
        $minutos = $hora->minute;
        if ($minutos != 0 && $minutos != 30) {
            return back()->withErrors(['hora' => 'El horario debe estar en intervalos de 30 minutos.'])->withInput();
        }

        // Validar disponibilidad (excluyendo la reserva actual)
        $existeReserva = Reserva::where('vehiculo_id', $validated['vehiculo_id'])
            ->where('fecha', $validated['fecha'])
            ->where('hora', $validated['hora'])
            ->where('id', '!=', $reserva->id)
            ->exists();

        if ($existeReserva) {
            return back()->withErrors(['hora' => 'Ya existe una reserva para este vehículo en la fecha y hora seleccionada.'])->withInput();
        }

        $reserva->update($validated);

        return redirect()->route('reservas.index')
            ->with('success', 'Reserva actualizada exitosamente.');
    }

    public function destroy(Reserva $reserva): RedirectResponse
    {
        $reserva->delete();

        return redirect()->route('reservas.index')
            ->with('success', 'Reserva eliminada exitosamente.');
    }

    /**
     * Generar array de horarios disponibles (7:00 AM a 7:00 PM en intervalos de 30 minutos)
     */
    private function generarHorarios(): array
    {
        $horarios = [];
        $hora = Carbon::parse('07:00');
        $horaFin = Carbon::parse('19:00');

        while ($hora->lte($horaFin)) {
            $horarios[] = $hora->format('H:i');
            $hora->addMinutes(30);
        }

        return $horarios;
    }
}
