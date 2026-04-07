<?php

namespace App\Http\Controllers\addons\included;

use App\helper\helper;
use App\Http\Controllers\Controller;
use App\Models\Currencies;
use App\Models\CurrencySettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurrencyController extends Controller
{
    public function index(Request $request)
    {
        // dd('123');
        if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1)) {
            $getcurrency = CurrencySettings::where('is_available', 1)->get();
        } else {
            $getcurrency = CurrencySettings::get();
        }

        return view('admin.currency_settings.index', compact("getcurrency"));
    }
    public function edit(Request $request)
    {
        $editcurrency = CurrencySettings::where('id', $request->id)->first();
        $currencies = Currencies::where('is_available', 1)->get();
        return view('admin.currency_settings.edit', compact("editcurrency", "currencies"));
    }

    public function update(Request $request, $id)
    {
        try {
            $currency = CurrencySettings::where('id', $id)->first();
            $currency->currency = $request->currency;
            $currency->exchange_rate = $request->exchange_rate;
            $currency->currency_position = $request->currency_position;
            $currency->currency_space = $request->currency_space;
            $currency->currency_formate = $request->currency_formate;
            $currency->decimal_separator = $request->decimal_separator;
            $currency->update();
            return redirect('admin/currency-settings')->with('success', trans('messages.success'));
        } catch (\Throwable $th) {
            return redirect('admin/currency-settings')->with('error', trans('messages.wrong'));
        }
    }
}
