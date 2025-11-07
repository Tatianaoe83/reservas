<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = [
            [
                'negocio' => 'Supermercado La Esquina',
                'direccion' => 'Av. Principal #123, Centro',
                'telefono' => '555-1234',
                'contacto' => 'Juan Pérez',
                'precio_venta' => 150.00,
            ],
            [
                'negocio' => 'Restaurante El Buen Sabor',
                'direccion' => 'Calle 5 de Mayo #456',
                'telefono' => '555-2345',
                'contacto' => 'María González',
                'precio_venta' => 180.00,
            ],
            [
                'negocio' => 'Tienda La Barata',
                'direccion' => 'Blvd. Reforma #789',
                'telefono' => '555-3456',
                'contacto' => 'Carlos Rodríguez',
                'precio_venta' => 140.00,
            ],
            [
                'negocio' => 'Carnicería El Matadero',
                'direccion' => 'Av. Independencia #321',
                'telefono' => '555-4567',
                'contacto' => 'Roberto Martínez',
                'precio_venta' => 200.00,
            ],
            [
                'negocio' => 'Pescadería El Mar',
                'direccion' => 'Calle del Puerto #654',
                'telefono' => '555-5678',
                'contacto' => 'Ana López',
                'precio_venta' => 170.00,
            ],
            [
                'negocio' => 'Frutas y Verduras Frescas',
                'direccion' => 'Mercado Central Local #12',
                'telefono' => '555-6789',
                'contacto' => 'Pedro Sánchez',
                'precio_venta' => 160.00,
            ],
            [
                'negocio' => 'Abarrotes Don Pepe',
                'direccion' => 'Av. Revolución #987',
                'telefono' => '555-7890',
                'contacto' => 'José Ramírez',
                'precio_venta' => 145.00,
            ],
            [
                'negocio' => 'Pollos y Carnes El Rancho',
                'direccion' => 'Calle Agricultura #147',
                'telefono' => '555-8901',
                'contacto' => 'Luis Fernández',
                'precio_venta' => 190.00,
            ],
            [
                'negocio' => 'Mariscos del Pacífico',
                'direccion' => 'Blvd. Costero #258',
                'telefono' => '555-9012',
                'contacto' => 'Carmen Torres',
                'precio_venta' => 220.00,
            ],
            [
                'negocio' => 'Panadería La Espiga',
                'direccion' => 'Av. Hidalgo #369',
                'telefono' => '555-0123',
                'contacto' => 'Miguel Herrera',
                'precio_venta' => 155.00,
            ],
            [
                'negocio' => 'Cafetería El Aroma',
                'direccion' => 'Calle Juárez #741',
                'telefono' => '555-1357',
                'contacto' => 'Patricia Díaz',
                'precio_venta' => 165.00,
            ],
            [
                'negocio' => 'Helados La Paletera',
                'direccion' => 'Av. Central #852',
                'telefono' => '555-2468',
                'contacto' => 'Fernando Morales',
                'precio_venta' => 175.00,
            ],
            [
                'negocio' => 'Tortillería El Maíz',
                'direccion' => 'Calle Zaragoza #963',
                'telefono' => '555-3579',
                'contacto' => 'Lucía Jiménez',
                'precio_venta' => 130.00,
            ],
            [
                'negocio' => 'Licores y Bebidas',
                'direccion' => 'Av. Libertad #159',
                'telefono' => '555-4680',
                'contacto' => 'Ricardo Vega',
                'precio_venta' => 185.00,
            ],
            [
                'negocio' => 'Farmacia La Salud',
                'direccion' => 'Blvd. Médicos #753',
                'telefono' => '555-5791',
                'contacto' => 'Sandra Mendoza',
                'precio_venta' => 195.00,
            ],
        ];

        foreach ($clientes as $cliente) {
            Cliente::create($cliente);
        }

        $this->command->info('Se crearon ' . count($clientes) . ' clientes.');
    }
}
