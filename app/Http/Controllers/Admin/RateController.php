<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Rate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RateController extends Controller
{
    public function index(): View
    {
        $rates = Rate::with(['currency', 'baseCurrency'])
            ->latest()
            ->paginate(20);

        return view('admin.rates.index', compact('rates'));
    }

    public function create(): View
    {
        $currencies = Currency::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('admin.rates.create', compact('currencies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'currency_id' => ['required', 'exists:currencies,id'],
            'base_currency_id' => ['required', 'exists:currencies,id', 'different:currency_id'],
            'buy_rate' => ['required', 'numeric', 'gt:0'],
            'sell_rate' => ['required', 'numeric', 'gt:0'],
            'buy_fee' => ['nullable', 'numeric', 'min:0'],
            'sell_fee' => ['nullable', 'numeric', 'min:0'],
            'min_amount' => ['nullable', 'numeric', 'min:0'],
            'max_amount' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Rate::updateOrCreate(
            [
                'currency_id' => $data['currency_id'],
                'base_currency_id' => $data['base_currency_id'],
            ],
            [
                'buy_rate' => $data['buy_rate'],
                'sell_rate' => $data['sell_rate'],
                'buy_fee' => $data['buy_fee'] ?? 0,
                'sell_fee' => $data['sell_fee'] ?? 0,
                'min_amount' => $data['min_amount'] ?? 0,
                'max_amount' => $data['max_amount'] ?? null,
                'is_active' => $request->boolean('is_active', true),
            ]
        );

        return redirect()
            ->route('admin.rates.index')
            ->with('success', 'Rate saved successfully.');
    }
}
