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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex justify-around">


                    <div class="max-w-sm w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
                        <div class="flex justify-between border-gray-200 border-b dark:border-gray-700 pb-3">
                            <dl>
                                <dt class="text-base font-normal text-gray-500 dark:text-gray-400 pb-1">Links
                                    Totales</dt>
                                <dd class="leading-none text-3xl font-bold text-gray-900 dark:text-white"
                                    id="total"></dd>
                            </dl>
                            <div>
                                <span
                                    class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-green-900 dark:text-green-300">
                                    <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M5 13V1m0 0L1 5m4-4 4 4" />
                                    </svg>
                                    <p id="tazaBendeficios"></p>
                                </span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 py-3">
                            <dl>
                                <dt class="text-base font-normal text-gray-500 dark:text-gray-400 pb-1">Links
                                    completados</dt>
                                <dd class="leading-none text-xl font-bold text-green-500 dark:text-green-400"
                                    id="completados">
                                </dd>
                            </dl>
                            <dl>
                                <dt class="text-base font-normal text-gray-500 dark:text-gray-400 pb-1">Links pendientes
                                </dt>
                                <dd class="leading-none text-xl font-bold text-red-600 dark:text-red-500"
                                    id="pendientes"></dd>
                            </dl>
                        </div>
                        <div id="bar-chart"></div>
                    </div>


                    <div class="max-w-sm w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
                        <div class="flex justify-between">
                            <div>
                                <h5 class="leading-none text-3xl font-bold text-gray-900 dark:text-white pb-2"
                                    id="completadosarea">
                                </h5>
                                <p class="text-base font-normal text-gray-500 dark:text-gray-400">links completados</p>
                            </div>
                            <div
                                class="flex items-center px-2.5 py-0.5 text-base font-semibold text-green-500 dark:text-green-500 text-center">
                                <p id="tazaBendeficiosarea"></p>
                                <svg class="w-3 h-3 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 14">
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
                            name: "Income",
                            color: "#31C48D",
                            data: data.data.total_por_mes.completados,
                        },
                        {
                            name: "Expense",
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
