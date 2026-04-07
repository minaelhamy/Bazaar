<?php

namespace App\Http\Controllers\web;

use App\Helpers\helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Stripe\Charge;
use Stripe\Stripe;

class WalletController extends Controller
{
    public function wallet(Request $request)
    {
        $storeinfo = helper::storeinfo($request->vendor);
        $gettransactions = Transaction::where('user_id', Auth::user()->id)->orderByDesc('id')->paginate(10)->onEachSide(0);
        return view('front.wallet', compact('storeinfo', 'gettransactions'));
    }
    public function addmoneywallet(Request $request)
    {
        $storeinfo = helper::storeinfo($request->vendor);
        $getpaymentmethods = Payment::select('id', 'unique_identifier', 'environment', 'payment_name', 'payment_type', 'currency', 'public_key', 'secret_key', 'encryption_key', 'image')->whereNotIn('payment_type', array(1, 6, 16))->where('vendor_id', $storeinfo->id)->where('is_available', 1)->where('is_activate', '1')->orderBy('reorder_id')->get();
        return view('front.addmoney', compact('storeinfo', 'getpaymentmethods'));
    }

    public function addwallet(Request $request)
    {
        if ($request->status == 1) {
            $vendor_id = Session::get('vendor_id');
            $amount = Session::get('grand_total');
            $transaction_type = Session::get('payment_type');
        } else {
            $vendor_id = $request->vendor_id;
            $amount = $request->amount;
            $transaction_type = $request->transaction_type;
        }

        try {
            $checkuser = User::where('id', Auth::user()->id)->where('is_available', 1)->where('is_deleted', 2)->where('type', 3)->first();
            if (empty($checkuser)) {
                return response()->json(["status" => 0, "message" => trans('messages.invalid_user')], 200);
            }
            if ($transaction_type == "") {
                return response()->json(["status" => 0, "message" => trans('messages.payment_selection_required')], 200);
            }
            if ($amount == "") {
                return response()->json(["status" => 0, "message" => trans('messages.enter_amount')], 200);
            }
            if ($transaction_type == "3") {
                $getstripe = Payment::select('environment', 'secret_key', 'currency')->where('payment_type', 3)->where('vendor_id', $request->vendor_id)->first();
                $skey = $getstripe->secret_key;
                $token = $request->transaction_id;

                Stripe::setApiKey($skey);
                $charge = Charge::create(
                    array(
                        'source' => $token,
                        'amount' => $amount * 100,
                        'currency' => $getstripe->currency,
                        'description' => 'Store-Mart',
                    )
                );
                if ($request->transaction_id == "") {
                    $transaction_id = $charge['id'];
                } else {
                    $transaction_id = $request->transaction_id;
                }
            } else {
                if ($request->transaction_id == "") {
                    return response()->json(["status" => 0, "message" => trans('messages.enter_transaction_id')], 200);
                }
                $transaction_id = $request->transaction_id;
            }

            // 3 = added-money-wallet-using- Razorpay 
            // 4 = added-money-wallet-using- Stripe 
            // 5 = added-money-wallet-using- Flutterwave 
            // 6 = added-money-wallet-using- Paystack
            // 7 = added-money-wallet-using- mecadopago
            // 8 = added-money-wallet-using- myfatoorah
            // 9 = added-money-wallet-using- paypal
            // 10 = added-money-wallet-using- toyyibpay

            $transaction = new Transaction();
            $transaction->vendor_id = $vendor_id;
            $transaction->user_id = $checkuser->id;
            $transaction->payment_id = $transaction_id;
            $transaction->payment_type = $transaction_type;
            $transaction->transaction_type = 1;
            $transaction->amount = $amount;
            $transaction->save();

            $checkuser->wallet += $amount;
            $checkuser->save();

            if ($transaction_type == 7 || $transaction_type == 8 || $transaction_type == 9 || $transaction_type == 10 || $transaction_type == 11 || $transaction_type == 12 || $transaction_type == 13 || $transaction_type == 14 || $transaction_type == 15) {
                return redirect(helper::storefront_url(Session::get('slug'), '/wallet'))->with('success', trans('messages.add_money_success'));
            }

            return response()->json(['status' => 1, 'message' => trans('messages.success')], 200);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['status' => 0, 'message' => trans('messages.wrong')], 200);
        }
    }


    public function addsuccess(Request $request)
    {
        try {

            if ($request->has('paymentId')) {
                $paymentId = request('paymentId');
                $response = ['status' => 1, 'msg' => 'paid', 'transaction_id' => $paymentId];
            }
            if ($request->has('payment_id')) {
                $paymentId = request('payment_id');
                $response = ['status' => 1, 'msg' => 'paid', 'transaction_id' => $paymentId];
            }

            if ($request->has('transaction_id')) {
                $paymentId = request('transaction_id');
                $response = ['status' => 1, 'msg' => 'paid', 'transaction_id' => $paymentId];
            }

            if (Session::get('payment_type') == "11") {
                if ($request->code == "PAYMENT_SUCCESS") {
                    $paymentId = $request->transactionId;
                    $response = ['status' => 1, 'msg' => 'paid', 'transaction_id' => $paymentId];
                } else {
                    return redirect(helper::storefront_url(Session::get('slug'), '/wallet'))->with('error', trans('messages.unable_to_complete_payment'));
                }
            }
            if (Session::get('payment_type') == "12") {
                $checkstatus = app('App\Http\Controllers\addons\PayTabController')->checkpaymentstatus(Session::get('tran_ref'), Session::get('vendor_id'));
                if ($checkstatus == "A") {
                    $paymentId = Session::get('tran_ref');
                    $response = ['status' => '1', 'msg' => 'paid', 'transaction_id' => $paymentId];
                } else {
                    return redirect(Session::get('failureurl'))->with('error', session()->get('paytab_response'));
                }
            }


            if (Session::get('payment_type') == "13") {
                $checkstatus = app('App\Http\Controllers\addons\MollieController')->checkpaymentstatus(Session::get('tran_ref'), Session::get('vendor_id'));

                if ($checkstatus == "A") {
                    $paymentId = Session::get('tran_ref');
                    $response = ['status' => 1, 'msg' => 'paid', 'transaction_id' => $paymentId];
                } else {
                    return redirect(Session::get('failureurl'))->with('error', session()->get('paytab_response'));
                }
            }

            if (Session::get('payment_type') == "14") {

                if ($request->status == "Completed") {
                    $paymentId = $request->transaction_id;
                    $response = ['status' => 1, 'msg' => 'paid', 'transaction_id' => $paymentId];
                } else {
                    return redirect(Session::get('failureurl'))->with('error', session()->get('paytab_response'));
                }
            }

            if (session()->get('payment_type') == "15") {

                $checkstatus = app('App\Http\Controllers\addons\XenditController')->checkpaymentstatus(session()->get('tran_ref'), Session::get('vendor_id'));

                if ($checkstatus == "PAID") {
                    $paymentId = session()->get('payment_id');
                    $response = ['status' => 1, 'msg' => 'paid', 'transaction_id' => $paymentId];
                } else {
                    return redirect(Session::get('failureurl'))->with('error', session()->get('paytab_response'));
                }
            }
        } catch (\Exception $e) {
            dd($e);
            $response = ['status' => 0, 'msg' => $e->getMessage()];
        }

        $request = new Request($response);
        return $this->addwallet($request);
    }

    public function addfail()
    {
        if (count(request()->all()) > 0) {
            return redirect(helper::storefront_url(Session::get('slug'), '/wallet'))->with('error', trans('messages.unable_to_complete_payment'));
        } else {
            return redirect(helper::storefront_url(Session::get('slug'), '/wallet'));
        }
    }
}
