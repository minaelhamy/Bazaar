<?php

namespace App\Http\Controllers\addons;

use App\Helpers\helper;
use App\Http\Controllers\Controller;
use App\Models\Currencies;
use App\Models\CurrencySettings;
use Illuminate\Http\Request;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class CurrencyController extends Controller
{
    public function add(Request $request)
    {
        $currencys = Currencies::get();
        return view('admin.currency_settings.add', compact('currencys'));
    }
    public function store(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }

        $currency = new CurrencySettings();
        $currency->code = $request->code;
        $currency->name = $request->name;
        $currency->currency = $request->currency_symbol;
        $currency->exchange_rate = $request->exchange_rate;
        $currency->currency_position = $request->currency_position;
        $currency->currency_space = $request->currency_space;
        $currency->currency_formate = $request->currency_formate;
        $currency->decimal_separator = $request->decimal_separator;
        $currency->is_available = 1;
        $currency->save();
        return redirect('admin/currency-settings/')->with('success', trans('messages.success'));
    }
    public function delete(Request $request)
    {
        try {
            $currency = CurrencySettings::find($request->id);
            $getdefault = Settings::get();
            $setactive = CurrencySettings::where('code', 'usd')->first();
            $setactive->is_available = 1;
            $setactive->update();
            foreach ($getdefault as $default) {
                $code = explode('|', $default->currencies);
                $key = array_search($currency->code, $code);
                if ($key !== false) {
                    unset($code[$key]);
                }
                Settings::where('vendor_id', $default->vendor_id)->update(array('currencies' => implode('|', $code)));
                Settings::where('default_currency', $currency->code)->update(array('default_currency' => "usd"));
                $currency->delete();
            }
            return redirect('admin/currency-settings')->with('success', trans('messages.success'))->withCookie(cookie()->forget('code'));
        } catch (\Throwable $th) {
            return redirect('admin/currency-settings')->with('error', trans('messages.wrong'));
        }
    }
    public function bulk_delete(Request $request)
    {
        try {
            foreach ($request->id as $id) {
                $currency = CurrencySettings::find($id);
                $getdefault = Settings::get();
                $setactive = CurrencySettings::where('code', 'usd')->first();
                $setactive->is_available = 1;
                $setactive->update();
                foreach ($getdefault as $default) {
                    $code = explode('|', $default->currencies);
                    $key = array_search($currency->code, $code);
                    if ($key !== false) {
                        unset($code[$key]);
                    }
                    Settings::where('vendor_id', $default->vendor_id)->update(array('currencies' => implode('|', $code)));
                    Settings::where('default_currency', $currency->code)->update(array('default_currency' => "usd"));
                    $currency->delete();
                }
            }
            return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 0, 'msg' => trans('messages.error')], 200);

        }
    }

    public function changestatus(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        if ($request->code == helper::appdata($vendor_id)->default_currency) {
            return redirect()->back()->with('error', trans('messages.remove_default_currency'));
        } else {
            if ($request->status == 2) {
                $getdefault = Settings::get();
                foreach ($getdefault as $default) {
                    $code = explode('|', $default->currencies);
                    $key = array_search($request->code, $code);
                    if ($key !== false) {
                        unset($code[$key]);
                    }
                    Settings::where('vendor_id', $default->vendor_id)->update(array('currencies' => implode('|', $code)));
                    Settings::where('default_currency', $request->code)->update(array('default_currency' => "usd"));
                }
            }
            CurrencySettings::where('code', $request->code)->update(['is_available' => $request->status]);
            return redirect('admin/currency-settings')->with('success', trans('messages.success'));
        }
    }

    public function currency_setting_status(Request $request)
    {

        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }

        $settingdata = Settings::where('vendor_id', $vendor_id)->first();
        if ($request->status == 2) {
            $code = explode('|', helper::appdata($vendor_id)->currencies);

            if (count($code) == 1) {
                return redirect()->back()->with('error', trans('messages.currency_required_msg'));
            }
            if ($request->code == helper::appdata($vendor_id)->default_currency) {
                return redirect()->back()->with('error', trans('messages.remove_default_currency'));
            }
            $key = array_search($request->code, $code);
            if ($key !== false) {
                unset($code[$key]);
                $settingdata->currencies = implode('|', $code);
            }
        }
        if ($request->status == 1) {
            if (helper::appdata($vendor_id)->currencies != "") {
                $code = explode('|', helper::appdata($vendor_id)->currencies);
                array_push($code, $request->code);
                $settingdata->currencies = implode('|', $code);
            } else {
                $settingdata->currencies = $request->code;
            }
        }
        $settingdata->update();
        return redirect('admin/currency-settings')->with('success', trans('messages.success'));
    }

    public function setdefault(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $settingdata = Settings::where('vendor_id', $vendor_id)->first();
        if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1)) {
            $currency = CurrencySettings::where('code', $request->code)->first();
            if ($currency->is_available == 2) {
                return redirect()->back()->with('error', trans('messages.not_available_currency'));
            } else {
                $settingdata->default_currency = $request->code;
                $settingdata->update();
                return redirect()->back()->with('success', trans('messages.success'));
            }
        } else {
            if (in_array($request->code, explode('|', $settingdata->currencies))) {
                $settingdata->default_currency = $request->code;
                $settingdata->update();
                return redirect()->back()->with('success', trans('messages.success'));
            } else {

                return redirect()->back()->with('error', trans('messages.not_available_currency'));
            }
        }
        session()->put('currency', $currency->currency);
    }



    //-------------------------------------------- Currencies Add Edit Delete Start -----------------------------------------------//

    public function currency_data(Request $request)
    {
        $getcurrency = Currencies::get();
        return view('admin.currency_settings.currencys.index', compact('getcurrency'));
    }
    public function currency_add(Request $request)
    {
        return view('admin.currency_settings.currencys.add');
    }
    public function currency_store(Request $request)
    {
        $validator = Validator::make(['currency' => $request->currency], [
            'currency' => 'required|unique:currencies',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', trans('messages.unique_currency'));
        }
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }

        $currency = new Currencies();
        $currency->currency = $request->currency;
        $slug = Str::slug($request->currency);
        $currency->code = $slug;
        $currency->currency_symbol = $request->currency_symbol;
        $currency->save();
        return redirect('admin/currencys/')->with('success', trans('messages.success'));
    }

    public function currency_edit(Request $request)
    {

        $editcurrency = Currencies::where('id', $request->id)->first();
        return view('admin.currency_settings.currencys.edit', compact("editcurrency"));
    }

    public function currency_update(Request $request, $id)
    {
        try {
            $currency = Currencies::where('id', $id)->first();
            $currency->currency = $request->currency;
            $slug = Str::slug($request->currency);
            $currency->code = $slug;
            $currency->currency_symbol = $request->currency_symbol;
            $currency->update();
            return redirect('admin/currencys')->with('success', trans('messages.success'));
        } catch (\Throwable $th) {
            return redirect('admin/currencys')->with('error', trans('messages.wrong'));
        }
    }
    public function currency_delete(Request $request)
    {
        try {
            $currency = Currencies::find($request->id);

            $currency->delete();
            return redirect('admin/currencys')->with('success', trans('messages.success'));
        } catch (\Throwable $th) {
            return redirect('admin/currencys')->with('error', trans('messages.wrong'));
        }
    }
    public function currencystatus(Request $request)
    {
        $currency = Currencies::where('code', $request->code)->first();
        if ($request->status == 2) {
            if ($request->code == 'usd') {
                $currency->is_available = 1;
            } else {
                $currency->is_available = $request->status;
            }
        }
        if ($request->status == 1) {
            $currency->is_available = $request->status;
        }
        $currency->update();


        return redirect('admin/currencys')->with('success', trans('messages.success'));
    }
    public function currency_bulk_delete(Request $request)
    {
        try {
            foreach ($request->id as $id) {
                $currency = Currencies::find($id);
                $currency->delete();
            }
            return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
           
        } catch (\Throwable $th) {
            return response()->json(['status' => 0, 'msg' => trans('messages.error')], 200);

        }
    }
    //-------------------------------------------- Currencies Add Edit Delete End -----------------------------------------------//

    public function change(Request $request)
    {
        $currency = CurrencySettings::where('code', $request->currency)->first();
        session()->put('currency', $currency->currency);
        return redirect()->back()->withCookie(cookie('code', $currency->code, 60 * 24 * 365));
    }
}
