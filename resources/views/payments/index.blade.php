<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pagos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Listado de Pagos</h3>

                    <!-- Contenedor para la tabla responsiva -->
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto border-collapse border border-gray-300 rounded-lg">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                    <th class="py-3 px-4 text-left">Nombre</th>
                                    <th class="py-3 px-4 text-left">Apellidos</th>
                                    <th class="py-3 px-4 text-left">Monto</th>
                                    <th class="py-3 px-4 text-left">Descripción</th>
                                    <th class="py-3 px-4 text-left">Fecha</th>
                                    <th class="py-3 px-4 text-left">Status</th>
                                    <th class="py-3 px-4 text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 text-sm">
                                @forelse ($payments as $payment)
                                    <tr class="border-b border-gray-300 hover:bg-gray-50">
                                        <td class="py-2 px-4">{{ $payment->name }}</td>
                                        <td class="py-2 px-4">{{ $payment->last_name }}</td>
                                        <td class="py-2 px-4">${{ number_format($payment->amount, 2) }}</td>
                                        <td class="py-2 px-4 truncate">{{ Str::limit($payment->description, 20) }}</td>
                                        <td class="py-2 px-4">{{ $payment->created_at }}</td>
                                        <td class="py-2 px-4">{{ $payment->status }}</td>
                                        <td class="py-2 px-4">
                                            <a href="{{ route('payment.show', $payment->id) }}"
                                                class="bg-blue-500 text-white py-1 px-2 rounded">Ver</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-3 px-4 text-center text-gray-500">
                                            No se encontraron pagos registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-6">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
