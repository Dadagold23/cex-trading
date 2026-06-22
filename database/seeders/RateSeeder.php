<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Rate;
use Illuminate\Database\Seeder;

class RateSeeder extends Seeder
{
    public function run(): void
    {
        $ngn = Currency::where('code', 'NGN')->first();
        $usd = Currency::where('code', 'USD')->first();
        $btc = Currency::where('code', 'BTC')->first();
        $eth = Currency::where('code', 'ETH')->first();
        $usdt = Currency::where('code', 'USDT')->first();

        $rows = array_filter([
            $btc && $ngn ? [
                'currency_id' => $btc->id,
                'base_currency_id' => $ngn->id,
                'buy_rate' => 145000000,
                'sell_rate' => 142500000,
                'buy_fee' => 0,
                'sell_fee' => 0,
                'min_amount' => 0.0001,
                'max_amount' => 10,
                'is_active' => true,
            ] : null,

            $eth && $ngn ? [
                'currency_id' => $eth->id,
                'base_currency_id' => $ngn->id,
                'buy_rate' => 8500000,
                'sell_rate' => 8300000,
                'buy_fee' => 0,
                'sell_fee' => 0,
                'min_amount' => 0.001,
                'max_amount' => 100,
                'is_active' => true,
            ] : null,

            $usdt && $ngn ? [
                'currency_id' => $usdt->id,
                'base_currency_id' => $ngn->id,
                'buy_rate' => 1750,
                'sell_rate' => 1680,
                'buy_fee' => 0,
                'sell_fee' => 0,
                'min_amount' => 10,
                'max_amount' => 100000,
                'is_active' => true,
            ] : null,

            $usdt && $usd ? [
                'currency_id' => $usdt->id,
                'base_currency_id' => $usd->id,
                'buy_rate' => 1.02,
                'sell_rate' => 0.99,
                'buy_fee' => 0,
                'sell_fee' => 0,
                'min_amount' => 10,
                'max_amount' => 100000,
                'is_active' => true,
            ] : null,
        ]);

        foreach ($rows as $row) {
            Rate::updateOrCreate(
                [
                    'currency_id' => $row['currency_id'],
                    'base_currency_id' => $row['base_currency_id'],
                ],
                $row
            );
        }
    }
}
