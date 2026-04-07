<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tax;
use App\Models\PricingPlan;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use DB;
class TaxController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $gettax = Tax::where('vendor_id', $vendor_id)->where('is_deleted',2)->orderBy('reorder_id')->get();
        return view('admin.tax.index', compact("gettax"));
    }
    public function add(Request $request)
    {
        return view('admin.tax.add');
    }
    public function save(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $tax = new Tax();
        $tax->vendor_id = $vendor_id;
        $tax->name = $request->name;
        $tax->type = $request->type;
        $tax->tax = $request->tax;
        $tax->is_available = 1;
        $tax->is_deleted = 2;
        $tax->save();
        return redirect('admin/tax/')->with('success', trans('messages.success'));
    }
    public function edit(Request $request)
    {
        $edittax = Tax::where('id', $request->id)->first();
        return view('admin.tax.edit', compact("edittax"));
    }
    public function update(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $tax = Tax::where('id', $request->id)->first();
        $tax->vendor_id = $vendor_id;
        $tax->name = $request->name;
        $tax->type = $request->type;
        $tax->tax = $request->tax;
        $tax->update();
        return redirect('admin/tax')->with('success', trans('messages.success'));
    }
    public function change_status(Request $request)
    {
        Tax::where('id', $request->id)->update(['is_available' => $request->status]);
        return redirect('admin/tax')->with('success', trans('messages.success'));
    }
    public function delete(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $checktax = Tax::where('id', $request->id)->first();
        if(Auth::user()->type == 1)
        {
            $getplan = PricingPlan::where(DB::Raw("FIND_IN_SET($checktax->id, replace(tax, '|', ','))"), '>', 0)->get();
            foreach($getplan as $plan)
            {
                $tax_id = explode('|', $plan->tax);
                $key = array_search($checktax->id, $tax_id);
                if ($key !== false) {
                    unset($tax_id[$key]);
                    PricingPlan::where('id',$plan->id)->update(array('tax' => implode('|', $tax_id)));
                }
            }
        }
        else{
            $getproduct = Item::where(DB::Raw("FIND_IN_SET($checktax->id, replace(tax, '|', ','))"), '>', 0)->get();
            foreach($getproduct as $product)
            {
                $tax = explode('|', $product->tax);
                $key = array_search($checktax->id, $tax);
                if ($key !== false) {
                    unset($tax[$key]);
                    Item::where('vendor_id', $vendor_id)->update(array('tax' => implode('|', $tax)));
                }
            }
        }
        $checktax->delete();
        return redirect('admin/tax')->with('success', trans('messages.success'));
    }
    public function reorder_tax(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $gettax = Tax::where('vendor_id', $vendor_id)->get();
        foreach ($gettax as $tax) {
            foreach ($request->order as $order) {
                $tax = Tax::where('id', $order['id'])->first();
                $tax->reorder_id = $order['position'];
                $tax->save();
            }
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
    }
    public function bulk_delete(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }

        foreach ($request->id as $id) {
            $checktax = Tax::where('id', $id)->first();
            if(Auth::user()->type == 1)
            {
                $getplan = PricingPlan::where(DB::Raw("FIND_IN_SET($checktax->id, replace(tax, '|', ','))"), '>', 0)->get();
                foreach($getplan as $plan)
                {
                    $tax_id = explode('|', $plan->tax);
                    $key = array_search($checktax->id, $tax_id);
                    if ($key !== false) {
                        unset($tax_id[$key]);
                        PricingPlan::where('id',$plan->id)->update(array('tax' => implode('|', $tax_id)));
                    }
                }
            }
            else{
                $getproduct = Item::where(DB::Raw("FIND_IN_SET($checktax->id, replace(tax, '|', ','))"), '>', 0)->get();
                foreach($getproduct as $product) {
                    $tax = explode('|', $product->tax);
                    $key = array_search($checktax->id, $tax);
                    if ($key !== false) {
                        unset($tax[$key]);
                        Item::where('vendor_id', $vendor_id)->update(array('tax' => implode('|', $tax)));
                    }
                }
            }
            $checktax->delete();
        }
          return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
    }
}
