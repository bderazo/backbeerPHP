<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Usuario;

class RegistroUsuariosTest extends TestCase
{
    /** @test */
    public function un_usuario_puede_registrarse()
    {
        $data = [
            'nombres' => 'Nombre de Usuario',
            'apellidos' => 'Apellidos de Usuario',
            'correo' => 'usuari@example.com',
            'password' => 'contraseña',
            'rol' => 'ADMIN',
            'registrado_por' => 'identificador_del_registrador'
        ];

        $response = $this->post('/api/usuario/crear', $data);
        // dd($response->getContent());

        $response->assertStatus(201); // Verifica si el usuario se registró correctamente
        $this->assertDatabaseHas('usuarios', ['correo' => 'usuario@example.com']);
    }

    public function test_list_users()
    {
        $response  = $this->post('/api/usuario/listar');
        // dd($response->getContent());

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'nombres',
                        'apellidos',
                        'correo',
                        'rol',
                        'identificacion',
                        'registrado_por',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]
        ])->assertStatus(200);
    }

    public function test_ver_usuario_existente()
    {
        $usuario = Usuario::first(); // Obtén un usuario existente de tu base de datos

        $response = $this->post("/api/usuario/ver/{$usuario->id}");
        
        $expectedJson = [
            'status' => 200,
            'message' => 'Datos de Usuario.',
            'data' => [
                'id' => $usuario->id,
                'nombres' => $usuario->nombres,
                'apellidos' => $usuario->apellidos,
                'correo' => $usuario->correo,
                'rol' => $usuario->rol,
                'identificacion' => null,
                'registrado_por' => $usuario->registrado_por,
                'created_at' => $usuario->created_at,
                'updated_at' => $usuario->updated_at
            ]
        ];

        $response->assertStatus(200)
            ->assertExactJson($expectedJson);
    }

    public function test_ver_usuario_no_existente()
    {
        $usuarioIdNoExistente = 'id_que_no_existe';

        $response = $this->post("/api/usuario/ver/{$usuarioIdNoExistente}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'No se encontro datos del Usuario indicado.',
                'data' => null
            ]);
    }
}
