<?php

use App\Http\Controllers\PaymentController;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/payment-webhook', [PaymentController::class, 'paymentWebhook'])->name('payment.payment_webhook');

Route::post('/prueba', function (Request $request) {
    $months = $request->input('months'); // Array dinámico de meses: ["September", "October", ..., "February"]

    // Verificar si el array de meses está vacío
    if (empty($months)) {
        return response()->json(['error' => 'El array de meses está vacío.'], 400);
    }

    // Obtener el primer y último mes del array
    $startMonth = reset($months); // Primer mes
    $endMonth = end($months);     // Último mes

    // Obtener el año actual
    $currentYear = Carbon::now()->year;

    // Calcular el año del mes de inicio y el mes de fin
    $startYear = strtolower($startMonth) === 'january' || array_search($startMonth, $months) > array_search('December', $months)
        ? $currentYear
        : $currentYear - 1;

    $endYear = strtolower($endMonth) === 'january' || array_search($endMonth, $months) > array_search('December', $months)
        ? $currentYear + 1
        : $currentYear;

    // Crear las fechas de inicio y fin
    $startDate = Carbon::create($startYear, date('n', strtotime($startMonth)), 1)->startOfDay();
    $endDate = Carbon::create($endYear, date('n', strtotime($endMonth)), 1)->endOfMonth()->endOfDay();

    if ($request->input('role') === 'Vendedor') {
        // Consultas para obtener los pagos completados y pendientes
        $completados = Payment::where('user_id', $request->input('user_id'))
            ->where('status', 'COMPLETED')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $pendientes = Payment::where('user_id', $request->input('user_id'))
            ->where('status', 'PENDING')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
    } else {
        $completados = Payment::where('status', 'COMPLETED')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $pendientes = Payment::where('status', 'PENDING')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
    }

    // Contar total de pagos completados y pendientes
    $completadosCount = $completados->count();
    $pendientesCount = $pendientes->count();

    // Calcular el total
    $total = $completadosCount + $pendientesCount;

    // Calcular la tasa de beneficios (porcentaje de completados)
    $tazaBeneficios = $total > 0 ? ($completadosCount / $total) * 100 : 0;

    // Agrupar los pagos por mes y año, y contar la cantidad de pagos por mes
    $completadosGrouped = $completados->groupBy(function ($payment) {
        return $payment->created_at->format('F Y'); // Agrupar por mes y año
    });

    $pendientesGrouped = $pendientes->groupBy(function ($payment) {
        return $payment->created_at->format('F Y'); // Agrupar por mes y año
    });

    // Crear arrays para completados y pendientes por mes
    $completadosArray = [];
    $pendientesArray = [];

    // Recorrer todos los meses en que se tienen pagos (completados o pendientes)
    $allMonths = array_merge(array_keys($completadosGrouped->toArray()), array_keys($pendientesGrouped->toArray()));
    $uniqueMonths = array_unique($allMonths); // Eliminar duplicados

    // Llenar los arrays para completados y pendientes
    foreach ($uniqueMonths as $monthYear) {
        // Completados
        $completadosArray[] = isset($completadosGrouped[$monthYear]) ? $completadosGrouped[$monthYear]->count() : 0;

        // Pendientes
        $pendientesArray[] = isset($pendientesGrouped[$monthYear]) ? $pendientesGrouped[$monthYear]->count() : 0;
    }

    // Obtener los meses en los que hay pagos
    $mesesUsados = $uniqueMonths;

    return response()->json([
        'status' => 'success',
        'data' => [
            'completados' => $completadosCount,
            'pendientes' => $pendientesCount,
            'total' => $total,
            'taza_beneficios' => $tazaBeneficios,
            'meses_usados' => $mesesUsados,
            'total_por_mes' => [
                'completados' => $completadosArray,
                'pendientes' => $pendientesArray
            ],
            'request' => $request->all(),
            'startMonth' => $startMonth,
            'startYear' => $startYear,
            'startDate' => $startDate,
            'endMonth' => $endMonth,
            'endYear' => $endYear,
            'endDate' => $endDate,
            'Request' => $request
        ]
    ]);
})->name('prueba');
