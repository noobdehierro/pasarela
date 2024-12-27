<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/send-payment-link', [PaymentController::class, 'sendPaymentLink'])->name('payment.send_payment_link');

    Route::get('/payments', [PaymentController::class, 'payments'])->name('payment.payments');

    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payment.show');
    
});

Route::get('/payment', [PaymentController::class, 'payment'])->name('payment.payment');

// Route::post('/update-status', [PaymentController::class, 'updateStatus'])->name('payment.update_status');

require __DIR__ . '/auth.php';
