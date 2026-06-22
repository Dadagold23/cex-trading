<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\TradeOrder;
use App\Models\Withdrawal;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function trades(): StreamedResponse
    {
        $filename = 'trades-report.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Date', 'Reference', 'User', 'Type', 'Currency', 'Base Currency',
                'Amount', 'Rate', 'Fee', 'Total', 'Status'
            ]);

            TradeOrder::with(['user', 'currency', 'baseCurrency'])
                ->latest()
                ->chunk(200, function ($trades) use ($handle) {
                    foreach ($trades as $trade) {
                        fputcsv($handle, [
                            $trade->created_at?->format('Y-m-d H:i:s'),
                            $trade->reference,
                            $trade->user?->name,
                            $trade->order_type,
                            $trade->currency?->code,
                            $trade->baseCurrency?->code,
                            $trade->amount,
                            $trade->rate,
                            $trade->fee,
                            $trade->total,
                            $trade->status,
                        ]);
                    }
                });

            fclose($handle);
        }, $filename);
    }

    public function deposits(): StreamedResponse
    {
        $filename = 'deposits-report.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Date', 'Reference', 'User', 'Currency', 'Amount', 'Method', 'Status'
            ]);

            Deposit::with(['user', 'currency'])
                ->latest()
                ->chunk(200, function ($deposits) use ($handle) {
                    foreach ($deposits as $deposit) {
                        fputcsv($handle, [
                            $deposit->created_at?->format('Y-m-d H:i:s'),
                            $deposit->reference,
                            $deposit->user?->name,
                            $deposit->currency?->code,
                            $deposit->amount,
                            $deposit->method,
                            $deposit->status,
                        ]);
                    }
                });

            fclose($handle);
        }, $filename);
    }

    public function withdrawals(): StreamedResponse
    {
        $filename = 'withdrawals-report.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Date', 'Reference', 'User', 'Currency', 'Amount', 'Fee', 'Net Amount', 'Status'
            ]);

            Withdrawal::with(['user', 'currency'])
                ->latest()
                ->chunk(200, function ($withdrawals) use ($handle) {
                    foreach ($withdrawals as $withdrawal) {
                        fputcsv($handle, [
                            $withdrawal->created_at?->format('Y-m-d H:i:s'),
                            $withdrawal->reference,
                            $withdrawal->user?->name,
                            $withdrawal->currency?->code,
                            $withdrawal->amount,
                            $withdrawal->fee,
                            $withdrawal->net_amount,
                            $withdrawal->status,
                        ]);
                    }
                });

            fclose($handle);
        }, $filename);
    }
}
