<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Pago') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6">Información del Pago</h3>

                    <!-- Contenedor de detalles -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div>
                            <strong class="text-gray-600">Nombre:</strong>
                            <p class="text-gray-800">{{ $payment->name }}</p>
                        </div>

                        <!-- Apellidos -->
                        <div>
                            <strong class="text-gray-600">Apellidos:</strong>
                            <p class="text-gray-800">{{ $payment->last_name }}</p>
                        </div>

                        <!-- Monto -->
                        <div>
                            <strong class="text-gray-600">Monto:</strong>
                            <p class="text-gray-800">${{ number_format($payment->amount, 2) }}</p>
                        </div>

                        <!-- Descripción -->
                        <div>
                            <strong class="text-gray-600">Descripción:</strong>
                            <p class="text-gray-800">{{ $payment->description }}</p>
                        </div>

                        <!-- Número de Cotización -->
                        <div>
                            <strong class="text-gray-600">Número de Cotización:</strong>
                            <p class="text-gray-800">{{ $payment->quotation_number ?? 'N/A' }}</p>
                        </div>

                        <!-- Correo Electrónico -->
                        <div>
                            <strong class="text-gray-600">Correo Electrónico:</strong>
                            <p class="text-gray-800">{{ $payment->email }}</p>
                        </div>

                        <!-- Marca -->
                        <div>
                            <strong class="text-gray-600">Marca:</strong>
                            <p class="text-gray-800">{{ $payment->brand }}</p>
                        </div>

                        <!-- URL -->
                        <div>
                            <strong class="text-gray-600">URL:</strong>
                            @if (filter_var($payment->url, FILTER_VALIDATE_URL))
                                <a href="{{ $payment->url }}" target="_blank" class="text-blue-500 hover:underline">Ver
                                    enlace</a>
                            @else
                                <p class="text-gray-800">N/A</p>
                            @endif
                        </div>

                        <!-- Fecha de creación -->
                        <div>
                            <strong class="text-gray-600">Fecha de Creación:</strong>
                            <p class="text-gray-800">{{ $payment->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <strong class="text-gray-600">Status:</strong>
                            <p class="text-gray-800">{{ $payment->status }}</p>
                        </div>

                        <!-- user_id -->
                        <div>
                            <strong class="text-gray-600">Id de Usuario:</strong>
                            <p class="text-gray-800">{{ $payment->user_id }}</p>
                        </div>

                        <!-- user_name -->
                        <div>
                            <strong class="text-gray-600">Nombre de Usuario:</strong>
                            <p class="text-gray-800">{{ $payment->user_name }}</p>
                        </div>

                        <!-- id_sucursal -->
                        <div>
                            <strong class="text-gray-600">Id de Sucursal:</strong>
                            <p class="text-gray-800">{{ $payment->id_sucursal }}</p>
                        </div>
                    </div>

                    <!-- Botón Volver -->
                    <div class="mt-8">
                        <a href="{{ route('payment.payments') }}"
                            class="inline-block bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded-lg">
                            Volver a la lista de pagos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
