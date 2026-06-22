<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DepositController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RateController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\TradeController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\WithdrawalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhook\CryptoDepositWebhookController;

Route::post('/register', [RegisterController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink']);
Route::get('/rates', [RateController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/wallets', [WalletController::class, 'index']);

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::patch('/profile', [ProfileController::class, 'update']);

    Route::get('/notifications', [NotificationController::class, 'index']);

    Route::get('/trades', [TradeController::class, 'index']);
    Route::post('/trades/buy', [TradeController::class, 'buy']);
    Route::post('/trades/sell', [TradeController::class, 'sell']);

    Route::get('/deposits', [DepositController::class, 'index']);
    Route::post('/deposits', [DepositController::class, 'store']);

    Route::get('/withdrawals', [WithdrawalController::class, 'index']);
    Route::post('/withdrawals', [WithdrawalController::class, 'store']);

    Route::post('/webhooks/crypto-deposits', [CryptoDepositWebhookController::class, 'handle']);

    Route::post('/webhooks/crypto-deposits', [CryptoDepositWebhookController::class, 'handle'])
    ->middleware('crypto.webhook');

});
