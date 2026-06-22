<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Rate;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $currencies = Currency::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $rates = Rate::with(['currency', 'baseCurrency'])
            ->where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        return view('web.home', compact('currencies', 'rates'));
    }
}
