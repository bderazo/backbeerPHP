<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Usuario;

class LoginUsuariosTest extends TestCase
{
    /** @test */
    public function test_inicio_de_sesion_exitoso()
{
    $data = [
        'correo' => 'bderazo10@gmail.com',
        'password' => '123456',
    ];

    $response = $this->post('/api/auth/login', $data);

    $response->assertStatus(200); // Verifica un código de estado 200 para un inicio de sesión exitoso
    $response->assertJsonStructure([
        'status',
        'authorisation' => [
            'data' => [
                'id',
                'nombres',
                'apellidos',
                'correo',
                'rol',
                'identificacion',
                'registrado_por',
                'created_at',
                'updated_at',
                'user_tarjeta' => [
                    [
                        'id',
                        'usuario_id',
                        'comercio_id',
                        'estado',
                        'img_perfil',
                        'img_portada',
                        'nombre',
                        'profesion',
                        'empresa',
                        'acreditaciones',
                        'telefono',
                        'direccion',
                        'correo',
                        'sitio_web',
                        'wallet',
                        'clics_realizados',
                        'clics_guardar',
                        'clics_correo',
                        'clics_sitio_web',
                        'created_at',
                        'updated_at',
                    ]
                ],
            ],
            'token',
            'type',
            'expires_in',
        ],
    ]);
}

public function test_fallo_por_campos_requeridos_en_inicio_de_sesion()
{
    $data = [
        // Campos requeridos no se proporcionan (correo y contraseña)
    ];

    $response = $this->post('/api/auth/login', $data);

    $response->assertStatus(400); // Verifica un código de estado 422 para un fallo de validación
    $response->assertJsonStructure([
        'status',
        'message',
        'data' => [
            // Verifica que se espera un mensaje de error
            'correo',
            'password',
        ],
    ]);
    
    $response->assertJson([
        'status' => 0,
        'message' => 'Error al intentar iniciar sesión.',
        'data' => [
            'correo' => 'El campo correo es requerido.',
            // Puedes agregar más aserciones para otros campos requeridos si es necesario
        ],
    ]);
}



}
