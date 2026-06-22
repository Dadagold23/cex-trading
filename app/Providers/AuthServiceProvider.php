<?php

namespace App\Providers;

use App\Models\Deposit;
use App\Models\Notification;
use App\Models\SupportTicket;
use App\Models\TradeOrder;
use App\Models\Withdrawal;
use App\Policies\DepositPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\SupportTicketPolicy;
use App\Policies\TradeOrderPolicy;
use App\Policies\WithdrawalPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        TradeOrder::class => TradeOrderPolicy::class,
        Deposit::class => DepositPolicy::class,
        Withdrawal::class => WithdrawalPolicy::class,
        SupportTicket::class => SupportTicketPolicy::class,
        Notification::class => NotificationPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
