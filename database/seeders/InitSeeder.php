<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Comercio;
use App\Models\Maquina;
use App\Models\Pulsera;

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Añade un usuario admin
        Usuario::create([
            'id' => '0000001',
            'nombres' => 'David',
            'apellidos' => 'Admin',
            'correo' => 'bderazo10@gmail.com',
            'password' => Hash::make('password'),
            'rol' => 'ADMIN',
            'tipo_usuario' => 'GOLD',
            'registrado_por'=>'Seeder'

        ]);
        // Añade un usuario cliente
        Usuario::create([
            'id' => '0000003',
            'nombres' => 'Bryan',
            'apellidos' => 'Espinoza',
            'correo' => 'bderazo@espe.edu.ec',
            'password' => Hash::make('beer2024'),
            'rol' => 'CLIENTE',
            'tipo_usuario' => 'BRONZE',
            'registrado_por'=>'Admin'
        ]);
        // Añade un usuario vendedor
        Usuario::create([
            'id' => '0000002',
            'nombres' => 'David',
            'apellidos' => 'Erazo',
            'correo' => 'daviderazodavid-123@hotmail.com',
            'password' => Hash::make('beer2024'),
            'rol' => 'VENDEDOR',
            'tipo_usuario' => 'SILVER',
            'registrado_por'=>'Admin'
        ]);
        // Añade un comercio
        Comercio::create([
            'nombre_comercial' => 'Royal Hub',
            'ruc' => '1234567890001',
            'direccion' => 'Eloy Alfaro y Portugal',
            'estado' => '1',
        ]);
        //Añade una maquina
        Maquina::create([
            'tipo_cerveza' => 'Roja',
            'ubicacion' => '1',
            'precio' => '4.99',
            'cantidad' => '100',
            'estado' => '0',
        ]);
        //Añade una maquina
        Maquina::create([
            'tipo_cerveza' => 'Negra',
            'ubicacion' => '2',
            'precio' => '5.99',
            'cantidad' => '100',
            'estado' => '0',
        ]);
        //Añade una maquina
        Maquina::create([
            'tipo_cerveza' => 'Rubia',
            'ubicacion' => '3',
            'precio' => '6.99',
            'cantidad' => '100',
            'estado' => '0',
        ]);
        //Añade una pulsera
        Pulsera::create([
            'cupo_maximo' => '0',
            'estado' => '0',
            'tipo_sensor' => 'RFID',
            'codigo_sensor' => '123456789',
        ]);
    }
}
