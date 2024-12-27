<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/payment-webhook', [PaymentController::class, 'paymentWebhook'])->name('payment.payment_webhook');
