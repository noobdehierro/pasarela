<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @if (session('success'))
        <div id="success-alert"
            class="flex items-center p-2 mb-4 text-sm text-green-700 bg-green-100 rounded-lg border border-green-300"
            role="alert">
            <span class="font-medium">¡Éxito!</span> {{ session('success') }}
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('form_data'))
                        <div id="form-output" class="mt-6 mb-6 p-4 border border-gray-300 rounded-lg bg-gray-50"
                            style="word-break: break-all">
                            <h3 class="text-lg font-semibold text-gray-800">Datos Ingresados:</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                @foreach (session('form_data') as $key => $value)
                                    <div>
                                        <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                        @if (filter_var($value, FILTER_VALIDATE_URL))
                                            <a href="{{ $value }}" target="_blank"
                                                class="mt-2 inline-block bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                                                Ir al pago
                                            </a>
                                        @else
                                            {{ $value }}
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif


                    <form action="{{ route('payment.send_payment_link') }}" method="POST">
                        @csrf

                        <!-- Contenedor de dos columnas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Campo Nombre -->
                            <div>
                                <label for="nombre" class="block mb-1 text-sm font-medium text-gray-900">
                                    Nombre <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nombre" name="nombre"
                                    class="border-gray-300 w-full p-2 border rounded-lg text-sm bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Ingrese su nombre" value="{{ old('nombre') }}">
                                @error('nombre')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Campo Apellidos -->
                            <div>
                                <label for="apellidos" class="block mb-1 text-sm font-medium text-gray-900">
                                    Apellidos <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="apellidos" name="apellidos"
                                    class="border-gray-300 w-full p-2 border rounded-lg text-sm bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Ingrese sus apellidos" value="{{ old('apellidos') }}">
                                @error('apellidos')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Campo Monto -->
                            <div>
                                <label for="monto" class="block mb-1 text-sm font-medium text-gray-900">
                                    Monto <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="monto" name="monto"
                                    class="border-gray-300 w-full p-2 border rounded-lg text-sm bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Ingrese el monto" value="{{ old('monto') }}">
                                @error('monto')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Campo Descripción -->
                            <div>
                                <label for="descripcion" class="block mb-1 text-sm font-medium text-gray-900">
                                    Descripción <span class="text-red-500">*</span>
                                </label>
                                <textarea id="descripcion" name="descripcion"
                                    class="border-gray-300 w-full p-2 border rounded-lg text-sm bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                    rows="1" placeholder="Ingrese una descripción">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Campo Número de Cotización -->
                            <div>
                                <label for="numero_cotizacion" class="block mb-1 text-sm font-medium text-gray-900">
                                    Número de Cotización
                                </label>
                                <input type="text" id="numero_cotizacion" name="numero_cotizacion"
                                    class="border-gray-300 w-full p-2 border rounded-lg text-sm bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Ingrese el número de cotización"
                                    value="{{ old('numero_cotizacion') }}">
                                @error('numero_cotizacion')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Campo Correo Electrónico -->
                            <div>
                                <label for="correo" class="block mb-1 text-sm font-medium text-gray-900">
                                    Correo Electrónico <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="correo" name="correo"
                                    class="border-gray-300 w-full p-2 border rounded-lg text-sm bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Ingrese su correo electrónico" value="{{ old('correo') }}">
                                @error('correo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Campo Marca -->
                            <div>
                                <label for="marca" class="block mb-1 text-sm font-medium text-gray-900">
                                    Marca <span class="text-red-500">*</span>
                                </label>
                                {{-- <input type="text" id="marca" name="marca"
                                    class="border-gray-300 w-full p-2 border rounded-lg text-sm bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Ingrese la marca" value="{{ old('marca') }}"> --}}
                                <select id="marca" name="marca"
                                    class="border-gray-300 w-full p-2 border rounded-lg text-sm bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Seleccione una marca</option>
                                    <option value="Casa de las lomas">Casa de las lomas</option>
                                </select>
                                @error('marca')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Botón de envío -->
                        <div class="mt-6">
                            <button type="submit"
                                class="border-gray-300 w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 rounded-lg focus:outline-none">
                                Enviar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para ocultar alertas de éxito -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.getElementById('success-alert');
            if (alert) {
                setTimeout(() => alert.style.display = 'none', 3000);
            }
        });
    </script>
</x-app-layout>
