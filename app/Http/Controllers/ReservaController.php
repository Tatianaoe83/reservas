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
            'estatus' => 'required|in:' . implode(',', array_keys(Reserva::ESTATUS)),
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
            $vehiculo = Vehiculo::find($validated['vehiculo_id']);
            return back()->withErrors([
                'hora' => "El vehículo '{$vehiculo->nombre}' ya tiene una reserva programada para el {$validated['fecha']} a las {$validated['hora']}. Por favor, seleccione otro horario o vehículo."
            ])->withInput();
        }

        // Si no se especifica estatus, usar 'programado' por defecto
        if (!isset($validated['estatus'])) {
            $validated['estatus'] = Reserva::ESTATUS_PROGRAMADO;
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
            'estatus' => 'required|in:' . implode(',', array_keys(Reserva::ESTATUS)),
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
            $vehiculo = Vehiculo::find($validated['vehiculo_id']);
            return back()->withErrors([
                'hora' => "El vehículo '{$vehiculo->nombre}' ya tiene una reserva programada para el {$validated['fecha']} a las {$validated['hora']}. Por favor, seleccione otro horario o vehículo."
            ])->withInput();
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
     * Obtener horarios disponibles para un vehículo en una fecha específica
     */
    public function getHorariosDisponibles(Request $request)
    {
        try {
            $validated = $request->validate([
                'vehiculo_id' => 'required|exists:vehiculos,id',
                'fecha' => 'required|date',
                'reserva_id' => 'nullable|exists:reservas,id', // Para edición
            ]);

            // Obtener todas las reservas para el vehículo en la fecha
            $query = Reserva::where('vehiculo_id', $validated['vehiculo_id'])
                ->where('fecha', $validated['fecha']);

            // Si hay una reserva_id, excluirla (para edición)
            if ($request->has('reserva_id') && $request->reserva_id) {
                $query->where('id', '!=', $request->reserva_id);
            }

            // Obtener las horas ocupadas y normalizarlas al formato H:i
            $reservas = $query->get()->map(function($reserva) {
                $hora = $reserva->hora;
                
                // Normalizar el formato de hora a H:i
                if (is_string($hora)) {
                    // Si viene como "07:30:00", tomar solo "07:30"
                    if (strlen($hora) >= 5) {
                        return substr($hora, 0, 5);
                    }
                    return $hora;
                } elseif ($hora instanceof \Carbon\Carbon || $hora instanceof \DateTime) {
                    return $hora->format('H:i');
                }
                
                return $hora;
            })->filter()->unique()->values()->toArray();

            // Generar todos los horarios posibles
            $todosHorarios = $this->generarHorarios();
            
            // Filtrar horarios ocupados (comparación estricta)
            $horariosDisponibles = array_filter($todosHorarios, function($horario) use ($reservas) {
                // Comparación estricta, asegurando que ambos sean strings en formato H:i
                return !in_array($horario, $reservas, true);
            });

            // Asegurarnos de que los arrays están en el formato correcto
            $horariosDisponibles = array_values($horariosDisponibles);
            $horariosOcupados = array_values(array_unique($reservas));

            // Log para depuración (remover en producción si es necesario)
            \Log::debug('Horarios disponibles', [
                'vehiculo_id' => $validated['vehiculo_id'],
                'fecha' => $validated['fecha'],
                'horarios_ocupados' => $horariosOcupados,
                'total_disponibles' => count($horariosDisponibles),
                'total_ocupados' => count($horariosOcupados),
            ]);

            return response()->json([
                'horarios' => $horariosDisponibles,
                'horarios_ocupados' => $horariosOcupados
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener horarios disponibles',
                'message' => $e->getMessage()
            ], 500);
        }
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
