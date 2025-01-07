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
                <div class="p-6 text-gray-900">


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
                        <div
                            class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between">
                            <div class="flex justify-between items-center pt-5">
                                <!-- Button -->
                                <button id="dropdownDefaultButton" data-dropdown-toggle="lastDaysdropdown"
                                    data-dropdown-placement="bottom"
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 text-center inline-flex items-center dark:hover:text-white"
                                    type="button">
                                    Last 6 months
                                    <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 4 4 4-4" />
                                    </svg>
                                </button>
                                <!-- Dropdown menu -->
                                <div id="lastDaysdropdown"
                                    class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                        aria-labelledby="dropdownDefaultButton">
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Yesterday</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Today</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last
                                                7 days</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last
                                                30 days</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last
                                                90 days</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last
                                                6 months</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last
                                                year</a>
                                        </li>
                                    </ul>
                                </div>
                                <a href="#"
                                    class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-blue-600 hover:text-blue-700 dark:hover:text-blue-500  hover:bg-gray-100 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 px-3 py-2">
                                    Revenue Report
                                    <svg class="w-2.5 h-2.5 ms-1.5 rtl:rotate-180" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 9 4-4-4-4" />
                                    </svg>
                                </a>
                            </div>
                        </div>
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
                const tazaBendeficios = document.getElementById('tazaBendeficios');

                completados.textContent = data.data.completados;
                pendientes.textContent = data.data.pendientes;
                total.textContent = data.data.total;
                tazaBendeficios.textContent = 'taza de beneficios: ' + data.data.taza_beneficios.toFixed(
                    2) + '%';

                const options = {
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
