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
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="bg-gray-50 text-xs text-gray-700 uppercase">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nombre</th>
                                    <th scope="col" class="px-6 py-3">Monto</th>
                                    <th scope="col" class="px-6 py-3">Descripción</th>
                                    <th scope="col" class="px-6 py-3">Fecha</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($payments as $payment)
                                    <tr class="bg-white border-b">
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $payment->name . ' ' . $payment->last_name }}
                                        </td>
                                        <td class="px-6 py-4">${{ $payment->amount }} MXN</td>
                                        <td class="px-6 py-4">{{ $payment->description }}</td>
                                        <td class="px-6 py-4">{{ $payment->created_at }}</td>
                                        <td class="px-6 py-4">{{ $payment->status }}</td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('payment.show', $payment->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
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
