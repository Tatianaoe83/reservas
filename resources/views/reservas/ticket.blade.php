<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Reserva #{{ $reserva->id }}</title>
    <style>
        :root {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #0f172a;
        }

        body {
            background: #e2e8f0;
            margin: 0;
            padding: 2rem;
        }

        .ticket {
            max-width: 720px;
            margin: 0 auto;
            background: #fff;
            border-radius: 1.5rem;
            padding: 2.25rem;
            box-shadow: 0 25px 60px rgba(15, 23, 42, 0.25);
            position: relative;
            overflow: hidden;
        }

        .ticket::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 1.5rem;
            padding: 1px;
            background: linear-gradient(135deg, #2563eb, #06b6d4);
            -webkit-mask:
                linear-gradient(#fff 0 0) content-box,
                linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.75rem;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .brand-avatar {
            width: 58px;
            height: 58px;
            border-radius: 18px;
            background: linear-gradient(135deg, #2563eb, #38bdf8);
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 700;
            font-size: 1.35rem;
            box-shadow: inset 0 0 12px rgba(255, 255, 255, 0.35);
        }

        .brand h1 {
            margin: 0;
            font-size: 1.45rem;
            line-height: 1.2;
        }

        .brand span {
            display: block;
            font-size: 0.9rem;
            color: #64748b;
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .back-btn,
        .print-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.7rem 1.7rem;
            border-radius: 9999px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .back-btn {
            background: #e2e8f0;
            color: #0f172a;
            text-decoration: none;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(15, 23, 42, 0.2);
        }

        .print-btn {
            background: #0f172a;
            color: #fff;
            font-weight: 600;
            box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4);
        }

        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.45);
        }

        .hero {
            background: linear-gradient(120deg, #1d4ed8, #22d3ee);
            border-radius: 1.25rem;
            color: #fff;
            padding: 1.35rem 1.6rem;
            margin-bottom: 1.75rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .hero::after {
            content: '';
            position: absolute;
            width: 240px;
            height: 240px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            top: -70px;
            right: -50px;
            filter: blur(1px);
        }

        .hero-info h2 {
            margin: 0;
            font-size: 1.9rem;
            letter-spacing: 0.02em;
        }

        .hero-info p {
            margin: 0.35rem 0 0;
            font-size: 0.95rem;
            opacity: 0.85;
        }

        .badge {
            align-self: center;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.35);
            padding: 0.45rem 1.2rem;
            border-radius: 9999px;
            font-weight: 600;
            letter-spacing: 0.06em;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 1.1rem;
            margin-bottom: 1.5rem;
        }

        .card {
            background: #f8fafc;
            border-radius: 1.1rem;
            padding: 1.1rem;
            border: 1px solid #e2e8f0;
        }

        .card span {
            display: block;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
            margin-bottom: 0.35rem;
        }

        .card strong {
            font-size: 1.08rem;
            color: #0f172a;
        }

        .detail-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            overflow: hidden;
            border-radius: 1.1rem;
            margin-bottom: 1.35rem;
            border: 1px solid #e2e8f0;
        }

        .detail-table th {
            background: #f1f5f9;
            text-align: left;
            padding: 0.95rem 1rem;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #475569;
        }

        .detail-table td {
            padding: 0.9rem 1rem;
            font-size: 0.95rem;
            border-top: 1px solid #e2e8f0;
        }

        .detail-table tr:nth-child(even) td {
            background: #f8fafc;
        }

        .totals {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .totals-card {
            min-width: 240px;
            background: #0f172a;
            color: #fff;
            border-radius: 1.1rem;
            padding: 1.1rem 1.6rem;
            text-align: right;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.35);
        }

        .totals-card span {
            display: block;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            opacity: 0.78;
        }

        .totals-card strong {
            font-size: 2.1rem;
            line-height: 1.2;
        }

        .notes {
            margin-top: 1.6rem;
            padding: 1rem 1.2rem;
            background: #fffbea;
            border-radius: 1rem;
            border: 1px solid #fde68a;
            font-size: 0.95rem;
            color: #78350f;
        }

        .tear-line {
            margin: 1.75rem 0;
            border-top: 1px dashed #cbd5f5;
        }

        .footer {
            margin-top: 1.25rem;
            text-align: center;
            color: #94a3b8;
            font-size: 0.85rem;
            letter-spacing: 0.02em;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                color-adjust: exact;
            }
            body {
                background: #fff !important;
                padding: 0 !important;
            }
            .ticket {
                box-shadow: none !important;
                border-radius: 0 !important;
                margin: 0 !important;
            }
            .ticket::before,
            .actions {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    @php
        $estatusLabel = \App\Models\Reserva::ESTATUS[$reserva->estatus ?? \App\Models\Reserva::ESTATUS_PROGRAMADO] ?? 'Programado';
        $tipoPagoText = \App\Models\Reserva::TIPOS_PAGO[$reserva->tipo_pago ?? \App\Models\Reserva::TIPO_PAGO_CONTADO] ?? 'Contado';
    @endphp

    <div class="ticket">
        <header>
            <div class="brand">
                <div class="brand-avatar">RZ</div>
                <div>
                    <h1>Reservas de Hielo</h1>
                    <span>Comprobante de entrega programada</span>
                </div>
            </div>
            <div class="actions">
                <a href="{{ route('reservas.show', $reserva) }}" class="back-btn">← Regresar</a>
                <button class="print-btn" onclick="window.print()">Imprimir ticket</button>
            </div>
        </header>

        <section class="hero">
            <div class="hero-info">
                <h2>Folio #{{ $reserva->id }}</h2>
                <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>
            </div>
            <div class="badge">{{ strtoupper($estatusLabel) }}</div>
        </section>

        <div class="grid">
            <div class="card">
                <span>Cliente</span>
                <strong>{{ $reserva->cliente->negocio }}</strong>
                <div>{{ $reserva->cliente->contacto ?? 'Contacto no registrado' }}</div>
                <div>{{ $reserva->cliente->telefono ?? 'Teléfono no registrado' }}</div>
            </div>
            <div class="card">
                <span>Dirección</span>
                <strong>{{ $reserva->cliente->direccion ?? 'Sin dirección registrada' }}</strong>
            </div>
            <div class="card">
                <span>Programación</span>
                <strong>{{ $reserva->fecha->format('d/m/Y') }}</strong>
                <div>{{ \Carbon\Carbon::parse($reserva->hora)->format('h:i A') }} · {{ ucfirst($reserva->fecha->locale('es')->isoFormat('dddd')) }}</div>
            </div>
            <div class="card">
                <span>Tipo de pago</span>
                <strong>{{ $tipoPagoText }}</strong>
            </div>
        </div>

        <table class="detail-table">
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Producto</td>
                    <td>{{ $reserva->cliente->producto->nombre ?? 'No especificado' }}</td>
                    <td>—</td>
                    <td>—</td>
                </tr>
                <tr>
                    <td>Vehículo</td>
                    <td>{{ $reserva->vehiculo->nombre }}</td>
                    <td>—</td>
                    <td>—</td>
                </tr>
                <tr>
                    <td>Marquetas</td>
                    <td>Precio unitario ${{ number_format($precioUnitario, 2) }}</td>
                    <td>{{ $reserva->cantidad }}</td>
                    <td>${{ number_format($precioUnitario * $reserva->cantidad, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="totals">
            <div class="totals-card">
                <span>Total a pagar</span>
                <strong>${{ number_format($total, 2) }}</strong>
            </div>
        </div>

        @if($reserva->observaciones)
            <div class="notes">
                <strong>Observaciones:</strong>
                <div>{{ $reserva->observaciones }}</div>
            </div>
        @endif

        <div class="tear-line"></div>

        <p class="footer">
            Gracias por confiar en nosotros. Presenta este ticket al momento de recibir tu pedido.
        </p>
    </div>
</body>
<script>
    window.addEventListener('load', () => {
        setTimeout(() => window.print(), 150);
    });
</script>
</html>

