<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de Cotización</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; margin-top: 20px;">
        <!-- Cabecera -->
        <tr>
            <td align="center" bgcolor="#4CAF50" style="padding: 20px 0;">
                <img src="{{ asset('images/gd-mexico.png') }}" alt="Logo" width="150" style="display: block;" />
            </td>
        </tr>
        <!-- Título -->
        <tr>
            <td align="center" style="padding: 20px;">
                <h1 style="color: #333333; font-size: 24px;">Estimado/a {{ $nombre }} {{ $apellidos }}</h1>
            </td>
        </tr>
        <!-- Contenido del mensaje -->
        <tr>
            <td style="padding: 20px;">
                <p style="color: #555555; font-size: 16px; line-height: 1.5;">
                    Hemos generado una cotización para el producto o servicio que nos solicitaste. A continuación, encontrarás el detalle:
                </p>
                <p style="color: #333333; font-size: 16px;"><strong>Marca:</strong> {{ $marca }}</p>
                <p style="color: #333333; font-size: 16px;"><strong>Monto:</strong> ${{ $monto }}</p>
                <p style="color: #333333; font-size: 16px;"><strong>Descripción:</strong> {{ $descripcion }}</p>
                <p style="color: #333333; font-size: 16px;"><strong>Número de Cotización:</strong> #{{ $numero_cotizacion }}</p>
            </td>
        </tr>
        <!-- Botón de acción -->
        <tr>
            <td align="center" style="padding: 20px;">
                <a href="{{ $url }}" style="text-decoration: none; background-color: #4CAF50; color: #ffffff; padding: 10px 20px; border-radius: 5px; font-size: 16px;">
                    Ir a Pagar
                </a>
            </td>
        </tr>
        <!-- Despedida -->
        <tr>
            <td style="padding: 20px;">
                <p style="color: #555555; font-size: 16px; line-height: 1.5;">
                    Si tienes alguna pregunta, no dudes en responder a este correo. ¡Estamos aquí para ayudarte!
                </p>
                <p style="color: #555555; font-size: 16px;">Saludos,<br>El equipo de GD Mexico</p>
            </td>
        </tr>
        <!-- Pie de página -->
        <tr>
            <td align="center" bgcolor="#f4f4f4" style="padding: 10px; color: #777777; font-size: 12px;">
                {{-- © 2023 [Nombre de la Empresa]. Todos los derechos reservados.<br>
                <a href="#" style="color: #777777; text-decoration: none;">Política de privacidad</a> | 
                <a href="#" style="color: #777777; text-decoration: none;">Cancelar suscripción</a> --}}
            </td>
        </tr>
    </table>
</body>
</html>
