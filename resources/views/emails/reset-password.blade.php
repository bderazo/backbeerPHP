<!DOCTYPE html>
<html>

<head>
    <title>Recuperación de contraseña</title>
</head>

<body style="width: 600px; margin: 0 auto; text-align: center;">
    <h1>Hola, usuario OnlyTap</h1>
    <p>Se solicitó un restablecimiento de contraseña para tu cuenta {{ $correo }}, haz clic en el botón que aparece a continuación para cambiar tu contraseña</p>
    <a href="https://onlytap.proatek.com/reset?token={{ $token }}&email={{$correo}}" style="background-color: #2D3747; color: #fff; padding: 10px 20px; text-decoration: none; display: inline-block;">Cambiar contraseña</a>
    <p>Si tú no realizaste la solicitud de cambio de contraseña, solo ignora este mensaje.</p>
    <p>Este enlace solo es válido dentro de los próximos 60 minutos</p>
    <p>¡Gracias por usar nuestra aplicación!</p>
    <p>Saludos,</p>
    <p>OnlyTap</p>
</body>
<br>
<div style="width: 600px; margin: 0 auto; text-align: center;">
    <p>
        Si tiene problemas para hacer clic en el botón "Cambiar contraseña", copie y pegue la URL a continuación en su navegador web: <a href="https://onlytap.proatek.com/reset?token={{ $token }}&email={{$correo}}" target="_blank">https://onlytap.proatek.com/reset?token={{ $token }}&email={{$correo}}</a></p>
    </p>
</div>

</html>