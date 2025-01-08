<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Models\Payment;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    $payments = Payment::orderBy('id', 'desc')->take(5)->get();
    return view('dashboard', compact('payments'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/send-payment-link', [PaymentController::class, 'sendPaymentLink'])->name('payment.send_payment_link');

    Route::get('/payments', [PaymentController::class, 'payments'])->name('payment.payments');

    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payment.show');

    Route::get('/generator', function () {
        return view('generator');
    })->name('generator');
});

Route::get('/payment', [PaymentController::class, 'payment'])->name('payment.payment');

// Route::post('/update-status', [PaymentController::class, 'updateStatus'])->name('payment.update_status');

require __DIR__ . '/auth.php';
