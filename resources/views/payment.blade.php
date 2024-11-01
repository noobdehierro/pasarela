<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Cotización</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-200 flex items-center justify-center min-h-screen p-6">
    <div id="cotizacion-container"
        class="max-w-2xl w-full bg-white shadow-2xl rounded-2xl p-10 border border-gray-300 relative">
        <!-- Logo -->
        <div class="flex justify-center mb-8">
            <img src="https://mexico.pochteca.net/wp-content/uploads/2020/09/logo-pochteca.webp"
                alt="Logo de la Empresa" class="w-48">
        </div>

        <!-- Título de la Cotización -->
        <h2 class="text-4xl font-extrabold text-center text-gray-800 mb-10">Detalles de Cotización</h2>

        <!-- Información de la Cotización -->
        <div class="space-y-6">
            <div class="border p-4 rounded-lg shadow-sm bg-gray-50">
                <label class="block text-gray-700 font-semibold text-lg">Nombre:</label>
                <p class="text-gray-600 text-lg">{{ $nombre }}</p>
            </div>

            <div class="border p-4 rounded-lg shadow-sm bg-gray-50">
                <label class="block text-gray-700 font-semibold text-lg">Apellidos:</label>
                <p class="text-gray-600 text-lg">{{ $apellidos }}</p>
            </div>

            <div class="border p-4 rounded-lg shadow-sm bg-gray-50">
                <label class="block text-gray-700 font-semibold text-lg">Monto:</label>
                <p class="text-gray-600 text-lg">${{ number_format($monto, 2) }}</p>
            </div>

            <div class="border p-4 rounded-lg shadow-sm bg-gray-50">
                <label class="block text-gray-700 font-semibold text-lg">Descripción:</label>
                <p class="text-gray-600 text-lg">{{ $descripcion }}</p>
            </div>

            <div class="border p-4 rounded-lg shadow-sm bg-gray-50">
                <label class="block text-gray-700 font-semibold text-lg">Número de Cotización:</label>
                <p class="text-gray-600 text-lg">#{{ $numero_cotizacion }}</p>
            </div>

            <div class="border p-4 rounded-lg shadow-sm bg-gray-50">
                <label class="block text-gray-700 font-semibold text-lg">Correo:</label>
                <p class="text-gray-600 text-lg">{{ $correo }}</p>
            </div>

            <div class="border p-4 rounded-lg shadow-sm bg-gray-50">
                <label class="block text-gray-700 font-semibold text-lg">Marca:</label>
                <p class="text-gray-600 text-lg">{{ $marca }}</p>
            </div>
        </div>

        <!-- Botón de PayPal -->
        <div class="mt-10 bg-blue-50 p-6 rounded-lg shadow-md">
            <h2 class="text-3xl font-semibold text-gray-800 mb-4 text-center">¡Listo para Pagar!</h2>
            <p class="text-lg text-gray-600 mb-6 text-center">Haz clic en el botón a continuación para completar tu
                compra.</p>
            <div id="paypal-button-container" class="flex justify-center"></div>
        </div>
    </div>

    <!-- Mensaje de Agradecimiento -->
    <div id="thank-you-message"
        class="hidden flex flex-col items-center justify-center bg-white shadow-2xl rounded-2xl p-10 border border-gray-300">
        <div class="flex justify-center mb-6">
            <img src="https://mexico.pochteca.net/wp-content/uploads/2020/09/logo-pochteca.webp"
                alt="Logo de la Empresa" class="w-32">
        </div>
        <h2 class="text-5xl font-extrabold text-gray-800 mb-4 text-center">¡Gracias por su compra!</h2>
        <p class="text-lg text-gray-600 text-center mb-6">Su transacción ha sido exitosa.<br>Recibirá un correo de
            confirmación pronto.</p>
    </div>

    <script
        src="https://www.paypal.com/sdk/js?client-id={{ $PAYPAL_CLIENT_ID }}&components=buttons&currency=MXN&buyer-country=MX">
    </script>

    <script>
        paypal.Buttons({
            style: {
                layout: 'vertical',
                color: 'blue',
                shape: 'pill',
                label: 'paypal'
            },
            createOrder: function(data, actions) {
                return actions.order.create({
                    application_context: {
                        shipping_preference: 'NO_SHIPPING'
                    },
                    purchase_units: [{
                        amount: {
                            currency_code: 'MXN',
                            value: '{{ $monto }}'
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    // Ocultar la cotización y mostrar el mensaje de agradecimiento
                    document.getElementById('cotizacion-container').style.display = 'none';
                    document.getElementById('thank-you-message').classList.remove('hidden');
                });
            },
            onError: function(err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error en el proceso de pago. Por favor, inténtelo de nuevo.'
                });
            },
            onCancel: function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cancelado',
                    text: 'La compra ha sido cancelada.'
                });
            }
        }).render('#paypal-button-container');
    </script>
</body>

</html>
