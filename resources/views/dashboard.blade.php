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
            <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 0C4.48 0 0 4.48 0 10s4.48 10 10 10 10-4.48 10-10S15.52 0 10 0zm0 18C5.58 18 2 14.42 2 10S5.58 2 10 2s8 3.58 8 8-3.58 8-8 8zm-1-13l-4 4h3v4h2v-4h3l-4-4z" />
            </svg>
            <span class="font-medium">¡Éxito!</span> {{ session('success') }}
        </div>
    @endif


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('payment.send_payment_link') }}" method="POST" class="space-y-6">
                        @csrf
                        @foreach ([['nombre', 'Nombre', 'Ingrese su nombre'], ['apellidos', 'Apellidos', 'Ingrese sus apellidos'], ['monto', 'Monto', 'Ingrese el monto', 'number'], ['descripcion', 'Descripción', 'Ingrese una descripción', 'textarea', 4], ['numero_cotizacion', 'Número de Cotización', 'Ingrese el número de cotización'], ['correo', 'Correo Electrónico', 'Ingrese su correo electrónico', 'email'], ['marca', 'Marca', 'Ingrese la marca']] as $field)
                            <div class="mb-4">
                                <label class="block mb-2 text-sm font-medium text-gray-900" for="{{ $field[0] }}">
                                    {{ $field[1] }}
                                </label>
                                @if (isset($field[3]) && $field[3] === 'textarea')
                                    <textarea id="{{ $field[0] }}" name="{{ $field[0] }}"
                                        class="bg-gray-50 border @error($field[0]) border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        placeholder="{{ $field[2] }}" rows="{{ $field[4] ?? 4 }}">{{ old($field[0]) }}</textarea>
                                @else
                                    <input type="{{ $field[3] ?? 'text' }}" id="{{ $field[0] }}"
                                        name="{{ $field[0] }}"
                                        class="bg-gray-50 border @error($field[0]) border-red-500 @else border-gray-300 @enderror text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        placeholder="{{ $field[2] }}" value="{{ old($field[0]) }}">
                                @endif
                                @error($field[0])
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach

                        <div class="flex items-center justify-between">
                            <button type="submit"
                                class="w-full bg-[#96002e] hover:bg-[#96002e] text-white font-bold py-2 rounded focus:outline-none focus:shadow-outline transition duration-200">
                                Enviar
                            </button>
                        </div>

                    </form>
                    <!-- Sección para mostrar los datos ingresados -->
                    @if (session('form_data'))
                        <div id="form-output" class="mt-6 p-4 border border-gray-300 rounded-lg bg-gray-50"
                            style="word-break: break-all">
                            <h3 class="text-lg font-semibold text-gray-800">Datos Ingresados:</h3>
                            <ul id="form-data-list" class="mt-2 space-y-2 text-sm text-gray-700">
                                @foreach (session('form_data') as $key => $value)
                                    <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                        {{ $value }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.getElementById('success-alert');
            if (alert) {
                setTimeout(() => {
                    alert.classList.add('opacity-0'); // Fade out
                    setTimeout(() => {
                        alert.style.display = 'none'; // Hide completely after fade
                    }, 300); // Wait for fade duration before hiding
                }, 3000); // Wait 3 seconds before starting fade
            }
        });
    </script>
</x-app-layout>
