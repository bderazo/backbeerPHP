<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Usuario;


class RegistroTarjetaTest extends TestCase
{

    public function test_creacion_de_tarjeta_exitoso()
{
    // Datos para la creación de la tarjeta (ajusta estos datos según tus requisitos)
    $data = [
        'usuario_id' => 'daccdf34-b95a-46b1-9865-848f61e201cf',
        'nombre' => 'Nombre de la tarjeta',
        'profesion' => 'Profesión de la tarjeta',
        'empresa' => 'Empresa de la tarjeta',
        'telefono' => '1234567890',
        'direccion' => 'Dirección de la tarjeta',
        'correo' => 'correo@ejemplo.com',
        'sitio_web' => 'www.ejemplo.com',
    ];

    // Realiza la solicitud para crear una tarjeta
    $response = $this->post('/api/usuario/tarjeta/crear', $data);

    // Decodifica la respuesta JSON
    $responseData = json_decode($response->getContent(), true);

    // Verifica si la respuesta tiene el estado 201 (creación exitosa)
    $response->assertStatus(201);

    // Obtén el código de la tarjeta desde la respuesta
    $codigoTarjeta = $responseData['data']['id'];

    // Ahora tienes el código de la tarjeta en la variable $codigoTarjeta
}


public function test_creacion_de_tarjeta_fallido_por_usuario_id_invalido()
{
    // Datos para la creación de la tarjeta con un usuario_id no válido
    $data = [
        'usuario_id' => 'usuario_id_invalido', // Proporciona un usuario_id que no es válido
        'nombre' => 'Nombre de la tarjeta',
        'profesion' => 'Profesión de la tarjeta',
        'empresa' => 'Empresa de la tarjeta',
        'telefono' => '1234567890',
        'direccion' => 'Dirección de la tarjeta',
        'correo' => 'correo@ejemplo.com',
        'sitio_web' => 'www.ejemplo.com',
    ];

    // Realiza la solicitud para crear una tarjeta
    $response = $this->post('/api/usuario/tarjeta/crear', $data);

    // Decodifica la respuesta JSON
    $responseData = json_decode($response->getContent(), true);

    // Verifica si la respuesta tiene el estado 422 (error de validación)
    $response->assertStatus(422);

    // Verifica que la respuesta contenga un mensaje de error relacionado con "usuario_id"
    $this->assertArrayHasKey('usuario_id', $responseData['data']);
    $this->assertEquals(
        ['El campo usuario id seleccionado no es válido.'],
        $responseData['data']['usuario_id']
    );
}


public function test_actualizacion_de_tarjeta_exitosa()
{
    // Supongamos que tienes una tarjeta existente con el ID 'EC735630'
    $tarjetaId = 'EC735630';

    // Datos para actualizar la tarjeta
    $data = [
        'nombre' => 'Jane Smith',
        'profesion' => 'Gerente de Proyectos',
        'empresa' => 'RoyalTech',
        'telefono' => '987-654-3210',
        'direccion' => '456 Elm Avenue, Townsville',
        'correo' => 'janesmith@example.com',
        'sitio_web' => 'www.janesmithprojects.com',
    ];

    // Realiza la solicitud para actualizar la tarjeta
    $response = $this->post("/api/usuario/tarjeta/actualizar/{$tarjetaId}", $data);

    // Decodifica la respuesta JSON
    $responseData = json_decode($response->getContent(), true);

    // Verifica si la respuesta tiene el estado 200 (éxito)
    $response->assertStatus(200);

    // Verifica que la respuesta contenga los datos actualizados de la tarjeta
    $this->assertEquals('Jane Smith', $responseData['data']['nombre']);
    $this->assertEquals('Gerente de Proyectos', $responseData['data']['profesion']);
    $this->assertEquals('RoyalTech', $responseData['data']['empresa']);
    $this->assertEquals('987-654-3210', $responseData['data']['telefono']);
    $this->assertEquals('456 Elm Avenue, Townsville', $responseData['data']['direccion']);
    $this->assertEquals('janesmith@example.com', $responseData['data']['correo']);
    $this->assertEquals('www.janesmithprojects.com', $responseData['data']['sitio_web']);
}


public function test_visualizacion_de_tarjeta_exitosa()
{
    // Reemplaza 'EC735630' con el ID de la tarjeta que deseas ver
    $tarjetaId = 'EC735630';

    $response = $this->post("/api/usuario/tarjeta/ver/{$tarjetaId}");

    $response->assertStatus(200); // Verifica un código de estado 200 (éxito)

    // Decodifica la respuesta JSON
    $responseData = json_decode($response->getContent(), true);

    // Verifica que el mensaje sea correcto
    $this->assertEquals('Tarjeta de usuario indicado.', $responseData['message']);

    // Verifica que los datos de la tarjeta sean correctos
    $this->assertEquals('EC735630', $responseData['data']['id']);
    $this->assertEquals('Jane Smith', $responseData['data']['nombre']);
    $this->assertEquals('Gerente de Proyectos', $responseData['data']['profesion']);

    // Puedes continuar verificando otros campos de la tarjeta aquí
    // ...

    // Verifica que las redes sociales estén presentes en la respuesta
    $this->assertArrayHasKey('sociales_tarjeta', $responseData['data']);

    // Verifica que haya al menos una red social
    $this->assertNotEmpty($responseData['data']['sociales_tarjeta']);

    // Puedes continuar verificando datos de las redes sociales aquí
    // ...

    // Verifica que las configuraciones de tarjeta estén presentes en la respuesta
    $this->assertArrayHasKey('configuraciones_tarjeta', $responseData['data']);

    // Puedes continuar verificando datos de configuraciones de tarjeta aquí
    // ...
}


public function test_visualizacion_de_tarjeta_que_no_existe()
{
    // Supongamos que $tarjetaId es el ID de una tarjeta que no existe
    $tarjetaId = 'ID_NO_EXISTENTE';

    $response = $this->post("/api/usuario/tarjeta/ver/{$tarjetaId}");

    $response->assertStatus(200); // Verifica un código de estado 200
    $response->assertJson([
        'status' => 200,
        'message' => 'No se encontro la Tarjeta solicitada.',
        'data' => null
    ]);
}

}
