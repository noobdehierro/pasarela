<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @if (session('success'))
        <div id="success-alert"
            class="flex items-center p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg border border-green-300"
            role="alert">
            <span class="font-semibold">¡Éxito!</span>
            <p class="ml-2">{{ session('success') }}</p>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Table Section -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="relative overflow-x-auto">
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
            </div>

            <!-- Cards Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Card 1 -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center justify-between pb-3 border-b">
                        <dl>
                            <dt class="text-base text-gray-500">Links Totales</dt>
                            <dd class="text-3xl font-bold text-gray-900" id="total">0</dd>
                        </dl>
                        <div class="flex items-center space-x-1 bg-green-100 text-green-800 px-2 py-1 rounded-md">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 13V1m0 0L1 5m4-4 4 4" />
                            </svg>
                            <p id="tazaBendeficios">0%</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 py-4">
                        <dl>
                            <dt class="text-base text-gray-500">Links completados</dt>
                            <dd class="text-xl font-bold text-green-500" id="completados">0</dd>
                        </dl>
                        <dl>
                            <dt class="text-base text-gray-500">Links pendientes</dt>
                            <dd class="text-xl font-bold text-red-600" id="pendientes">0</dd>
                        </dl>
                    </div>
                    <div id="bar-chart"></div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h5 class="text-3xl font-bold text-gray-900" id="completadosarea">0</h5>
                            <p class="text-base text-gray-500">Links completados</p>
                        </div>
                        <div class="flex items-center text-green-500 space-x-1">
                            <p id="tazaBendeficiosarea">0%</p>
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 13V1m0 0L1 5m4-4 4 4" />
                            </svg>
                        </div>
                    </div>
                    <div id="area-chart"></div>
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

            fetch('{{ route('prueba') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    "X-CSRF-Token": document.querySelector('input[name=_token]').value
                },
                body: JSON.stringify({
                    user_id: {{ auth()->user()->id }},
                    role: '{{ auth()->user()->roles->first()->name }}',
                    months: getLastSixMonths()
                })
            }).then(response => response.json()).then(data => {

                console.log(data)

                const completados = document.getElementById('completados');
                const pendientes = document.getElementById('pendientes');
                const total = document.getElementById('total');
                const completadosarea = document.getElementById('completadosarea');
                const tazaBendeficios = document.getElementById('tazaBendeficios');
                const tazaBendeficiosarea = document.getElementById('tazaBendeficiosarea');

                completadosarea.textContent = data.data.completados;
                completados.textContent = data.data.completados;
                pendientes.textContent = data.data.pendientes;
                total.textContent = data.data.total;
                tazaBendeficios.textContent = 'taza de beneficios: ' + data.data.taza_beneficios.toFixed(
                    2) + '%';
                tazaBendeficiosarea.textContent = data.data.taza_beneficios.toFixed(2) + '%';

                var options = {
                    series: [{
                            name: "Completados",
                            color: "#31C48D",
                            data: data.data.total_por_mes.completados,
                        },
                        {
                            name: "Pendientes",
                            data: data.data.total_por_mes.pendientes,
                            color: "#F05252",
                        }
                    ],
                    chart: {
                        sparkline: {
                            enabled: false,
                        },
                        type: "bar",
                        width: "100%",
                        height: 400,
                        toolbar: {
                            show: false,
                        }
                    },
                    fill: {
                        opacity: 1,
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            columnWidth: "100%",
                            borderRadiusApplication: "end",
                            borderRadius: 6,
                            dataLabels: {
                                position: "top",
                            },
                        },
                    },
                    legend: {
                        show: true,
                        position: "bottom",
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        formatter: function(value) {
                            return "$" + value
                        }
                    },
                    xaxis: {
                        labels: {
                            show: true,
                            style: {
                                fontFamily: "Inter, sans-serif",
                                cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
                            },
                            formatter: function(value) {
                                return value
                            }
                        },
                        categories: data.data.meses_usados,
                        axisTicks: {
                            show: false,
                        },
                        axisBorder: {
                            show: false,
                        },
                    },
                    yaxis: {
                        labels: {
                            show: true,
                            style: {
                                fontFamily: "Inter, sans-serif",
                                cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
                            }
                        }
                    },
                    grid: {
                        show: true,
                        strokeDashArray: 4,
                        padding: {
                            left: 2,
                            right: 2,
                            top: -20
                        },
                    },
                    fill: {
                        opacity: 1,
                    }
                }

                if (document.getElementById("bar-chart") && typeof ApexCharts !== 'undefined') {
                    const chart = new ApexCharts(document.getElementById("bar-chart"), options);
                    chart.render();
                }


                var options = {
                    chart: {
                        height: "100%",
                        maxWidth: "100%",
                        type: "area",
                        fontFamily: "Inter, sans-serif",
                        dropShadow: {
                            enabled: false,
                        },
                        toolbar: {
                            show: false,
                        },
                    },
                    tooltip: {
                        enabled: true,
                        x: {
                            show: false,
                        },
                    },
                    fill: {
                        type: "gradient",
                        gradient: {
                            opacityFrom: 0.55,
                            opacityTo: 0,
                            shade: "#1C64F2",
                            gradientToColors: ["#1C64F2"],
                        },
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        width: 6,
                    },
                    grid: {
                        show: false,
                        strokeDashArray: 4,
                        padding: {
                            left: 2,
                            right: 2,
                            top: 0
                        },
                    },
                    series: [{
                        name: "Completados",
                        data: data.data.total_por_mes.completados,
                        color: "#1A56DB",
                    }, ],
                    xaxis: {
                        categories: data.data.meses_usados,
                        labels: {
                            show: false,
                        },
                        axisBorder: {
                            show: false,
                        },
                        axisTicks: {
                            show: false,
                        },
                    },
                    yaxis: {
                        show: false,
                    },
                }

                if (document.getElementById("area-chart") && typeof ApexCharts !== 'undefined') {
                    const chart = new ApexCharts(document.getElementById("area-chart"), options);
                    chart.render();
                }


            })

            function getLastSixMonths(spanish = false) {
                const monthsInEnglish = [
                    "january", "february", "march", "april", "may", "june",
                    "july", "august", "september", "october", "november", "december"
                ];
                const monthsInSpanish = [
                    "enero", "febrero", "marzo", "abril", "mayo", "junio",
                    "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"
                ];

                const currentDate = new Date();
                const lastSixMonthsInEnglish = [];
                const lastSixMonthsInSpanish = [];

                for (let i = 0; i < 6; i++) {
                    const monthIndex = (currentDate.getMonth() - i + 12) % 12; // Asegura que sea un índice válido
                    lastSixMonthsInEnglish.unshift(monthsInEnglish[
                        monthIndex]); // Agrega el mes al inicio de la lista
                    lastSixMonthsInSpanish.unshift(monthsInSpanish[
                        monthIndex]); // Agrega el mes al inicio de la lista
                }

                return spanish ? lastSixMonthsInSpanish : lastSixMonthsInEnglish;
            }

        });
    </script>
</x-app-layout>
