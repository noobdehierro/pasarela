<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;
use App\Models\Payment;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    if (auth()->user()->hasRole('Supervisor')) {
        $payments = Payment::orderBy('id', 'desc')->take(5)->get();
    } else {
        $payments = Payment::orderBy('id', 'desc')->take(5)->where('user_id', auth()->user()->id)->get();
    }
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

    Route::middleware(RoleMiddleware::class . ':Supervisor')->group(function () {

        Route::get('/users', [UserController::class, 'index'])->name('user.index');

        Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show');

        Route::get('/userEdit/{user}', [UserController::class, 'edit'])->name('user.edit');

        Route::put('/userEdit/{user}', [UserController::class, 'update'])->name('user.update');

        Route::get('/userCreate', [UserController::class, 'create'])->name('user.create');

        Route::post('/userRegister', [UserController::class, 'store'])->name('user.store');
    });
});

Route::get('/payment', [PaymentController::class, 'payment'])->name('payment.payment');

// Route::post('/update-status', [PaymentController::class, 'updateStatus'])->name('payment.update_status');

require __DIR__ . '/auth.php';
