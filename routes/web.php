<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DepositController as AdminDepositController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\KycController as AdminKycController;
use App\Http\Controllers\Admin\LiquidityController;
use App\Http\Controllers\Admin\RateController as AdminRateController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SupportTicketController as AdminSupportTicketController;
use App\Http\Controllers\Admin\TradeController as AdminTradeController;
use App\Http\Controllers\Admin\WithdrawalController as AdminWithdrawalController;
use App\Http\Controllers\User\BankAccountController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\DepositController as UserDepositController;
use App\Http\Controllers\User\KycController as UserKycController;
use App\Http\Controllers\User\NotificationController as UserNotificationController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\SecurityController;
use App\Http\Controllers\User\SupportTicketController as UserSupportTicketController;
use App\Http\Controllers\User\TradeController;
use App\Http\Controllers\User\TransactionController;
use App\Http\Controllers\User\WalletController;
use App\Http\Controllers\User\WithdrawalController as UserWithdrawalController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\CryptoWalletController;
use App\Http\Controllers\Admin\UserCryptoWalletController as AdminUserCryptoWalletController;
use App\Http\Controllers\Auth\TwoFactorChallengeController;


Route::middleware(['maintenance'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::view('/about', 'web.about')->name('about');
    Route::view('/rates', 'web.rates')->name('rates');
    Route::view('/how-it-works', 'web.how-it-works')->name('how-it-works');
    Route::view('/contact', 'web.contact')->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

    Route::get('two-factor-challenge', [TwoFactorChallengeController::class, 'create'])
    ->name('two-factor.challenge');
    Route::post('two-factor-challenge', [TwoFactorChallengeController::class, 'store'])
    ->name('two-factor.challenge.store');

});

Route::middleware(['auth', 'verified', 'user', 'maintenance'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

        Route::get('/wallets', [WalletController::class, 'index'])->name('wallets.index');
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

        Route::middleware('trade.enabled')->group(function () {
            Route::get('/trades', [TradeController::class, 'index'])->name('trades.index');
            Route::get('/buy', [TradeController::class, 'buy'])->name('trades.buy');
            Route::post('/buy', [TradeController::class, 'storeBuy'])->name('trades.buy.store');
            Route::get('/sell', [TradeController::class, 'sell'])->name('trades.sell');
            Route::post('/sell', [TradeController::class, 'storeSell'])->name('trades.sell.store');
        });

        Route::middleware('deposit.enabled')->group(function () {
            Route::get('/deposits', [UserDepositController::class, 'index'])->name('deposits.index');
            Route::get('/deposits/create', [UserDepositController::class, 'create'])->name('deposits.create');
            Route::post('/deposits', [UserDepositController::class, 'store'])->name('deposits.store');
            Route::get('/deposits/{deposit}', [UserDepositController::class, 'show'])->name('deposits.show');
        });

        Route::middleware('withdrawal.enabled')->group(function () {
            Route::get('/withdrawals', [UserWithdrawalController::class, 'index'])->name('withdrawals.index');
            Route::get('/withdrawals/create', [UserWithdrawalController::class, 'create'])->name('withdrawals.create');
            Route::post('/withdrawals', [UserWithdrawalController::class, 'store'])->name('withdrawals.store');
            Route::get('/withdrawals/{withdrawal}', [UserWithdrawalController::class, 'show'])->name('withdrawals.show');

            Route::post('/crypto-wallets/{currency}/regenerate', [\App\Http\Controllers\User\CryptoWalletController::class, 'regenerate'])
                ->name('crypto-wallets.regenerate');

            Route::get('/security/two-factor', [\App\Http\Controllers\User\TwoFactorController::class, 'index'])->name('two-factor.index');
            Route::post('/security/two-factor/start', [\App\Http\Controllers\User\TwoFactorController::class, 'start'])->name('two-factor.start');
            Route::post('/security/two-factor/confirm', [\App\Http\Controllers\User\TwoFactorController::class, 'confirm'])->name('two-factor.confirm');
            Route::post('/security/two-factor/disable', [\App\Http\Controllers\User\TwoFactorController::class, 'disable'])->name('two-factor.disable');
            Route::post('/security/two-factor/recovery-codes', [\App\Http\Controllers\User\TwoFactorController::class, 'regenerateRecoveryCodes'])->name('two-factor.recovery-codes');

        });

        Route::get('/bank-accounts', [BankAccountController::class, 'index'])->name('bank-accounts.index');
        Route::get('/bank-accounts/create', [BankAccountController::class, 'create'])->name('bank-accounts.create');
        Route::post('/bank-accounts', [BankAccountController::class, 'store'])->name('bank-accounts.store');

        Route::get('/kyc', [UserKycController::class, 'index'])->name('kyc.index');
        Route::post('/kyc', [UserKycController::class, 'store'])->name('kyc.store');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('/security', [SecurityController::class, 'index'])->name('security.index');
        Route::patch('/security', [SecurityController::class, 'update'])->name('security.update');

        Route::get('/notifications', [UserNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/read-all', [UserNotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        Route::post('/notifications/{notification}/read', [UserNotificationController::class, 'markAsRead'])->name('notifications.read');

        Route::get('/support', [UserSupportTicketController::class, 'index'])->name('support.index');
        Route::get('/support/create', [UserSupportTicketController::class, 'create'])->name('support.create');
        Route::post('/support', [UserSupportTicketController::class, 'store'])->name('support.store');
        Route::get('/support/{ticket}', [UserSupportTicketController::class, 'show'])->name('support.show');
        Route::post('/support/{ticket}/reply', [UserSupportTicketController::class, 'reply'])->name('support.reply');

        Route::get('/crypto-wallets', [CryptoWalletController::class, 'index'])->name('crypto-wallets.index');
        Route::post('/crypto-wallets/{currency}/regenerate', [CryptoWalletController::class, 'regenerate'])->name('crypto-wallets.regenerate');

        Route::get('/crypto-wallets/{currency}', [CryptoWalletController::class, 'show'])->name('crypto-wallets.show');
        
        Route::get('/crypto-wallets/{currency}/deposit', [CryptoWalletController::class, 'deposit'])->name('crypto-wallets.deposit');
        
    });

Route::middleware(['auth', 'verified', 'admin', 'maintenance'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/trades', [AdminTradeController::class, 'index'])->name('trades.index');
        Route::get('/trades/{trade}', [AdminTradeController::class, 'show'])->name('trades.show');
        Route::post('/trades/{trade}/approve', [AdminTradeController::class, 'approve'])->name('trades.approve');
        Route::post('/trades/{trade}/reject', [AdminTradeController::class, 'reject'])->name('trades.reject');

        Route::get('/rates', [AdminRateController::class, 'index'])->name('rates.index');
        Route::get('/rates/create', [AdminRateController::class, 'create'])->name('rates.create');
        Route::post('/rates', [AdminRateController::class, 'store'])->name('rates.store');

        Route::get('/deposits', [AdminDepositController::class, 'index'])->name('deposits.index');
        Route::get('/deposits/{deposit}', [AdminDepositController::class, 'show'])->name('deposits.show');
        Route::post('/deposits/{deposit}/approve', [AdminDepositController::class, 'approve'])->name('deposits.approve');
        Route::post('/deposits/{deposit}/reject', [AdminDepositController::class, 'reject'])->name('deposits.reject');

        Route::get('/withdrawals', [AdminWithdrawalController::class, 'index'])->name('withdrawals.index');
        Route::get('/withdrawals/{withdrawal}', [AdminWithdrawalController::class, 'show'])->name('withdrawals.show');
        Route::post('/withdrawals/{withdrawal}/approve', [AdminWithdrawalController::class, 'approve'])->name('withdrawals.approve');
        Route::post('/withdrawals/{withdrawal}/reject', [AdminWithdrawalController::class, 'reject'])->name('withdrawals.reject');

        Route::get('/kyc', [AdminKycController::class, 'index'])->name('kyc.index');
        Route::get('/kyc/{kyc}', [AdminKycController::class, 'show'])->name('kyc.show');
        Route::post('/kyc/{kyc}/approve', [AdminKycController::class, 'approve'])->name('kyc.approve');
        Route::post('/kyc/{kyc}/reject', [AdminKycController::class, 'reject'])->name('kyc.reject');

        Route::get('/liquidity', [LiquidityController::class, 'index'])->name('liquidity.index');
        Route::post('/liquidity', [LiquidityController::class, 'store'])->name('liquidity.store');

        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

        Route::get('/support', [AdminSupportTicketController::class, 'index'])->name('support.index');
        Route::get('/support/{ticket}', [AdminSupportTicketController::class, 'show'])->name('support.show');
        Route::post('/support/{ticket}/reply', [AdminSupportTicketController::class, 'reply'])->name('support.reply');
        Route::post('/support/{ticket}/status', [AdminSupportTicketController::class, 'updateStatus'])->name('support.status');

        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

        Route::get('/admin-users', [AdminUserController::class, 'index'])->name('admin-users.index');
        Route::get('/admin-users/create', [AdminUserController::class, 'create'])->name('admin-users.create');
        Route::post('/admin-users', [AdminUserController::class, 'store'])->name('admin-users.store');
        Route::get('/admin-users/{adminUser}/edit', [AdminUserController::class, 'edit'])->name('admin-users.edit');
        Route::patch('/admin-users/{adminUser}', [AdminUserController::class, 'update'])->name('admin-users.update');
        Route::middleware(['auth', 'admin', 'admin.2fa'])->prefix('admin')->name('admin.')->group(function () {
    // admin routes
});


        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/trades', [ReportController::class, 'trades'])->name('reports.trades');
        Route::get('/reports/deposits', [ReportController::class, 'deposits'])->name('reports.deposits');
        Route::get('/reports/withdrawals', [ReportController::class, 'withdrawals'])->name('reports.withdrawals');
        Route::get('/reports/users', [ReportController::class, 'users'])->name('reports.users');
        Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
        Route::get('/reports/chart-data', [ReportController::class, 'chartData'])->name('reports.chart-data');

        Route::get('/exports/trades', [ExportController::class, 'trades'])->name('exports.trades');
        Route::get('/exports/deposits', [ExportController::class, 'deposits'])->name('exports.deposits');
        Route::get('/exports/withdrawals', [ExportController::class, 'withdrawals'])->name('exports.withdrawals');
        
        Route::get('/crypto-wallets', [AdminUserCryptoWalletController::class, 'index'])->name('crypto-wallets.index');

        Route::post('/crypto-wallets/{cryptoWallet}/rotate', [AdminUserCryptoWalletController::class, 'rotate'])
        ->name('crypto-wallets.rotate');
        Route::get('/deposit-monitoring', [\App\Http\Controllers\Admin\DepositMonitoringController::class, 'index'])
            ->name('deposit-monitoring.index');
        Route::get('/deposits/export', [\App\Http\Controllers\Admin\DepositController::class, 'export'])
            ->name('deposits.export');
        Route::get('/withdrawals/export', [\App\Http\Controllers\Admin\WithdrawalController::class, 'export'])
            ->name('withdrawals.export');
        Route::get('/trades/export', [\App\Http\Controllers\Admin\TradeController::class, 'export'])
            ->name('trades.export');

    });


require __DIR__.'/auth.php';
