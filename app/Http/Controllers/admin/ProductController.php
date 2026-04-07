<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\helper;
use App\Models\Category;
use App\Models\Item;
use App\Models\Variants;
use App\Models\Cart;
use App\Models\Extra;
use App\Models\Testimonials;
use App\Models\ProductImage;
use App\Models\GlobalExtras;
use App\Models\Banner;
use App\Models\Tax;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use PDF;

class ProductController extends Controller
{
    public function index()
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $getproductslist = Item::with('variation', 'category_info', 'product_image')->where('vendor_id',  $vendor_id)->where('is_deleted', 2)->orderBy('reorder_id')->get();
        return view('admin.product.product', compact('getproductslist'));
    }
    public function add(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $checkplan = helper::checkplan($vendor_id, '');
        $v = json_decode(json_encode($checkplan));
        if (@$v->original->status == 2) {
            return redirect('admin/products')->with('error', @$v->original->message);
        }
        $globalextras = GlobalExtras::where('vendor_id', $vendor_id)->where('is_available', 1)->orderBy('reorder_id')->get();
        $getcategorylist = Category::where('is_available', 1)->where('is_deleted', 2)->where('vendor_id',  $vendor_id)->orderBy('reorder_id')->get();
        $gettaxlist = Tax::where('vendor_id', $vendor_id)->where('is_deleted', 2)->where('is_available', 1)->orderBy('reorder_id')->get();
        $getitems = Item::where('vendor_id', $vendor_id)->where('is_available', 1)->where('is_deleted', 2)->orderBy('reorder_id')->get();
        return view('admin.product.add_product', compact("getcategorylist", "globalextras", "gettaxlist", "getitems"));
    }
    public function save(Request $request)
    {
        if ($request->has('product_image')) {
            $validator = Validator::make($request->all(), [
                'product_image.*' => 'image|max:' . helper::imagesize() . '|' . helper::imageext(),
            ], [
                'product_image.max' => trans('messages.image_size_message'),
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with('error', trans('messages.image_size_message') . ' ' . helper::appdata('')->image_size . ' ' . 'MB');
            }
        }

        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $checkplan = helper::checkplan($vendor_id, '');
        $v = json_decode(json_encode($checkplan));
        if (@$v->original->status == 2) {
            return redirect('admin/products')->with('error', @$v->original->message);
        }
        if ($request->has_variants == 1) {
            if ($request->hiddenVariantOptions == "{}") {
                return redirect('admin/products/add')->with('error', trans('messages.variation_required'));
            }
        }

        $check_slug = Item::where('slug', Str::slug($request->product_name, '-'))->first();
        if (!empty($check_slug)) {
            $last_id = Item::select('id')->orderByDesc('id')->first()->id;
            $slug = Str::slug($request->product_name . ' ' . $last_id, '-');
        } else {
            $slug = Str::slug($request->product_name, '-');
        }
        $price = $request->price;
        $original_price = $request->original_price;

        $product = new Item();
        if ($request->has_variants == 1) {
            $variants_json = json_decode($request->hiddenVariantOptions, true);
            $variant_options = array_column($variants_json, 'variant_options');
            $possibilities = Item::possibleVariants($variant_options);

            foreach ($possibilities as $key => $possibility) {
                $price = $request->verians[$key]['price'];
                $qty = $request->verians[$key]['qty'];
                $original_price = $request->verians[$key]['original_price'];
                break;
            }
        } else {
            $price = $request->price;
            $original_price = $request->original_price;
            $qty = $request->qty;
        }
        $product->vendor_id = $vendor_id;
        $product->cat_id =  implode('|', $request->category);
        $product->item_name = $request->product_name;
        $product->item_price = $price;
        $product->item_original_price = $original_price;
        $product->sku = $request->product_sku;
        $product->slug = $slug;
        $product->has_variants = $request->has_variants;
        $product->tax = $request->tax != null ? implode('|', $request->tax) : '';
        $product->frequently_bought_items = $request->frequently_bought_items != null ? implode('|', $request->frequently_bought_items) : null;
        $product->description = $request->description;
        $product->video_url = $request->video_url;
        $product->is_imported = 2;
        if ($request->has_variants == 1) {
            $product->variants_json = $request->hiddenVariantOptions;
        } else {
            $product->variants_json = "";
        }
        $product->stock_management = $request->has_stock;
        if ($request->has_stock == 1) {
            $product->qty = $qty;
            $product->min_order = $request->min_order;
            $product->max_order = $request->max_order;
            $product->low_qty = $request->low_qty;
        } else {
            $product->qty = "";
            $product->low_qty = "";
            $product->min_order = "";
            $product->max_order = "";
        }

        $product->attchment_name = $request->attachment_name;
        if ($request->has('attachment_file')) {
            $reimage = 'attachment-' . uniqid() . "." . $request->file('attachment_file')->getClientOriginalExtension();
            $request->file('attachment_file')->move(storage_path('app/public/admin-assets/images/product/'), $reimage);
            $product->attchment_file = $reimage;
        }


        if ($request->has('downloadfile')) {
            $reimage = 'download-' . uniqid() . "." . $request->file('downloadfile')->getClientOriginalExtension();
            $request->file('downloadfile')->move(storage_path('app/public/admin-assets/images/product/'), $reimage);
            $product->download_file = $reimage;
        }

        $product->save();

        if ($request->has('product_image')) {
            foreach ($request->file('product_image') as $file) {
                $reimage = 'item-' . uniqid() . "." . $file->getClientOriginalExtension();
                $file->move(storage_path('app/public/item/'), $reimage);
                // $imgname = helper::imageresize($file, storage_path('app/public/item'));
                $image = new ProductImage();
                $image->vendor_id =  $vendor_id;
                $image->item_id = $product->id;
                $image->image = $reimage;
                $image->save();
            }
        }

        if ($request->has_variants == 1) {
            $product->variants_json = json_decode($product->variants_json, true);
            $variant_options = array_column($product->variants_json, 'variant_options');
            $possibilities = Item::possibleVariants($variant_options);
            foreach ($possibilities as $key => $possibility) {
                $VariantOption = new Variants();
                $VariantOption->name = $possibility;
                $VariantOption->item_id = $product->id;
                $VariantOption->price =  array_key_exists('price', $request->verians[$key]) ? $request->verians[$key]['price'] : '';
                $VariantOption->qty = array_key_exists('qty', $request->verians[$key]) ? $request->verians[$key]['qty'] : '';
                $VariantOption->original_price = array_key_exists('original_price', $request->verians[$key]) ? $request->verians[$key]['original_price'] : '';
                $VariantOption->min_order =  array_key_exists('min_order', $request->verians[$key]) ? $request->verians[$key]['min_order'] : '';
                $VariantOption->max_order =  array_key_exists('max_order', $request->verians[$key]) ?  $request->verians[$key]['max_order'] : '';
                $VariantOption->low_qty =  array_key_exists('low_qty', $request->verians[$key]) ? $request->verians[$key]['low_qty'] : '';
                $VariantOption->stock_management = array_key_exists('stock_management', $request->verians[$key]) ? $request->verians[$key]['stock_management'] : 2;
                $VariantOption->is_available = array_key_exists('is_available', $request->verians[$key]) ? 1 : 2;
                $VariantOption->save();
            }
        }
        if ($request->extras_name != null && $request->extras_name != "") {
            foreach ($request->extras_name as $key => $no) {
                if (@$no != "" && @$request->extras_price[$key] != "") {
                    $extras = new Extra();
                    $extras->item_id = $product->id;
                    $extras->name = $no;
                    $extras->price = $request->extras_price[$key];
                    $extras->save();
                }
            }
        }
        return redirect('admin/products/')->with('success', trans('messages.success'));
    }
    public function edit($slug)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $globalextras = GlobalExtras::where('vendor_id', $vendor_id)->where('is_available', 1)->orderBy('reorder_id')->get();
        $getproductdata = Item::with('category_info', 'product_image', 'extras', 'multi_image')->where('slug', $slug)->first();
        $gettaxlist = Tax::where('vendor_id', $vendor_id)->where('is_deleted', 2)->where('is_available', 1)->orderBy('reorder_id')->get();
        $productVariantArrays = [];
        if (!empty($getproductdata)) {
            $getcategorylist = Category::where('is_available', 1)->where('is_deleted', 2)->where('vendor_id', $vendor_id)->orderBy('reorder_id')->get();
            $productreview = Testimonials::where('item_id', $getproductdata->id)->where('vendor_id', $vendor_id)->get();
            $getitems = Item::where('vendor_id', $vendor_id)->whereNot('id', $getproductdata->id)->where('is_available', 1)->where('is_deleted', 2)->orderBy('reorder_id')->get();


            $product_variant_names = [];
            $variant_options = [];
            if ($getproductdata->has_variants == '1') {
                $productVariants = Variants::where('item_id', $getproductdata->id)->get();

                if (!empty(json_decode($getproductdata->variants_json, true))) {

                    $variant_options = array_column(json_decode($getproductdata->variants_json), 'variant_name');
                    $product_variant_names = $variant_options;
                }

                foreach ($productVariants as $key => $productVariant) {
                    $productVariantArrays[$key]['product_variants'] = $productVariant->toArray();
                }
            }

            return view('admin.product.edit_product', compact('getproductdata', 'getcategorylist', 'productreview', 'gettaxlist', 'productVariantArrays', 'product_variant_names', 'variant_options', 'globalextras', 'getitems'));
        }
        return redirect('admin/products')->with('error', trans('messages.wrong'));
    }

    public function update_product(Request $request, $slug)
    {
        try {

            $price = $request->price;
            $original_price = $request->original_price;
            if ($request->has_variants == 1) {
                if ($request->verians == null && $request->variants == null) {
                    return redirect('admin/products/edit-' . $request->slug)->with('error', trans('messages.variation_required'));
                }
            }
            if ($request->has_variants == 1) {
                $variants_json = json_decode($request->hiddenVariantOptions, true);
                $variant_options = array_column($variants_json, 'variant_options');
                $newpossibilities = Item::possibleVariants($variant_options);
                if ($request->verians == null) {
                    foreach ($request->variants as $key => $possibility) {
                        $price = $request->variants[$key]['price'];
                        $qty = $request->variants[$key]['qty'];
                        $original_price = $request->variants[$key]['original_price'];
                        break;
                    }
                } else {
                    foreach ($newpossibilities as $key => $possibility) {
                        $price = $request->verians[$key]['price'];
                        $qty = $request->verians[$key]['qty'];
                        $original_price = $request->verians[$key]['original_price'];
                        break;
                    }
                }
            } else {
                $price = $request->price;
                $original_price = $request->original_price;
                $qty = $request->qty;
            }
            $product = Item::where('slug', $request->slug)->first();
            $product->cat_id = implode('|', $request->category);
            $product->item_name = $request->product_name;
            $product->item_price = $price;
            $product->item_original_price = $price;
            $product->item_original_price = $original_price;
            $product->sku = $request->product_sku;
            if ($request->has_variants == '1') {

                $product['has_variants'] = '1';
                $product['variants_json'] = !empty($request->hiddenVariantOptions) ? $request->hiddenVariantOptions : $product->variants_json;

                if (!empty($request->verians) && count($request->verians) > 0) {

                    foreach ($request->verians as $key => $possibility) {
                        $possibilities = Variants::find($key);
                        if (is_null($possibilities)) {
                            $VariantOptionNew = new Variants();
                            $VariantOptionNew->item_id = $product->id;
                            $VariantOptionNew->name = $possibility['name'];
                            $VariantOptionNew->price = $possibility['price'];
                            $VariantOptionNew->original_price = $possibility['original_price'];
                            $VariantOptionNew->qty = $possibility['qty'] ?? $possibility['qty'];
                            $VariantOptionNew->min_order = $possibility['min_order'] ?? $possibility['min_order'];
                            $VariantOptionNew->max_order = $possibility['max_order'] ?? $possibility['max_order'];
                            $VariantOptionNew->low_qty =  $possibility['low_qty'];
                            $VariantOptionNew->stock_management = array_key_exists('stock_management', $possibility) ? $possibility['stock_management'] : 2;
                            $VariantOptionNew->is_available = array_key_exists('is_available',  $possibility) ?  $possibility['is_available'] : 2;
                            $VariantOptionNew->save();
                        } else {

                            $possibilities->price = $possibility['price'];
                            $possibilities->original_price = $possibility['original_price'];
                            $possibilities->qty = $possibility['qty'] ?? $possibility['qty'];
                            $possibilities->min_order = $possibility['min_order'] ?? $possibility['min_order'];
                            $possibilities->max_order = $possibility['max_order'] ?? $possibility['max_order'];
                            $possibilities->low_qty = $possibility['low_qty'] ?? $possibility['low_qty'];
                            $possibilities->stock_management = array_key_exists('stock_management', $possibility) ? $possibility['stock_management'] : 2;
                            $possibilities->is_available = array_key_exists('is_available',  $possibility) ?  $possibility['is_available'] : 2;
                            $possibilities->save();
                        }
                    }
                } else if (!empty($request->variants) && count($request->variants) > 0) {

                    foreach ($request->variants as $key => $possibility) {
                        $possibilities = Variants::find($key);
                        $possibilities->price = $possibility['price'];
                        $possibilities->original_price = $possibility['original_price'];
                        $possibilities->qty = $possibility['qty'] ?? $possibility['qty'];
                        $possibilities->min_order = $possibility['min_order'] ?? $possibility['min_order'];
                        $possibilities->max_order = $possibility['max_order'] ?? $possibility['max_order'];
                        $possibilities->low_qty = $possibility['low_qty'] ?? $possibility['low_qty'];
                        $possibilities->stock_management = array_key_exists('stock_management', $possibility) ? $possibility['stock_management'] : 2;
                        $possibilities->is_available = array_key_exists('is_available',  $possibility) ?  $possibility['is_available'] : 2;
                        if (!array_key_exists('is_available',  $possibility)) {

                            $carts = Cart::where('variants_id', $possibilities->id)->get();
                            foreach ($carts as $cart) {
                                $cart->delete();
                            }
                        }
                        $possibilities->save();
                    }
                }
            } else {
                $product['has_variants'] = '0';
            }

            $product->has_variants = $request->has_variants;
            $product->tax = $request->tax != null ? implode('|', $request->tax) : '';
            $product->frequently_bought_items = $request->frequently_bought_items != null ? implode('|', $request->frequently_bought_items) : null;

            $product->description = $request->description;
            $product->video_url = $request->video_url;
            if ($request->has_variants == 1) {
                $product->attribute = $request->attribute;
            } else {
                $product->attribute = "";
            }
            $product->stock_management = $request->has_stock;
            if ($request->has_stock == 1) {
                $product->qty = $qty;
                $product->low_qty = $request->low_qty;
                $product->min_order = $request->min_order;
                $product->max_order = $request->max_order;
            } else {
                $product->qty = "";
                $product->low_qty = "";
                $product->min_order = "";
                $product->max_order = "";
            }
            $product->attchment_name = $request->attachment_name;

            if ($request->has('attachment_file')) {
                if ($product->attchment_file != "" && $product->attchment_file != null && file_exists(storage_path('app/public/admin-assets/images/product/' . $product->attchment_file))) {
                    unlink(storage_path('app/public/admin-assets/images/product/' . $product->attchment_file));
                }
                $reimage = 'attachment-' . uniqid() . "." . $request->file('attachment_file')->getClientOriginalExtension();
                $request->file('attachment_file')->move(storage_path('app/public/admin-assets/images/product/'), $reimage);
                $product->attchment_file = $reimage;
            }


            if ($request->has('downloadfile')) {
                if ($product->download_file != "" && $product->download_file != null && file_exists(storage_path('app/public/admin-assets/images/product/' . $product->download_file))) {
                    unlink(storage_path('app/public/admin-assets/images/product/' . $product->download_file));
                }
                $reimage = 'download-' . uniqid() . "." . $request->file('downloadfile')->getClientOriginalExtension();
                $request->file('downloadfile')->move(storage_path('app/public/admin-assets/images/product/'), $reimage);
                $product->download_file = $reimage;
            }

            if ($request->has_variants == 2) {
                Variants::where('item_id', $product->id)->delete();
                $product->variants_json = '';
            }
            $carts = Cart::where('item_id', $product->id)->delete();
            $product->update();
            if ($request->has_variants == 1) {
                if (!empty($request->variants)) {

                    foreach ($request->variants as $key => $variant) {
                        $newVal = '';

                        foreach (array_values($variant['variants']) as $k => $v) {
                            if (!empty($newVal)) {
                                $newVal .= '|' . $v[0];
                            } else {
                                $newVal .= $v[0];
                            }
                        }
                        $VariantOption = Variants::find($key);
                        $VariantOption->name = $newVal;
                        $VariantOption->price = $variant['price'];
                        $VariantOption->original_price = $variant['original_price'];
                        $VariantOption->qty = $variant['qty'] ?? $variant['qty'];
                        $VariantOption->min_order = $variant['min_order'] ?? $variant['min_order'];
                        $VariantOption->max_order = $variant['max_order'] ?? $variant['max_order'];
                        $VariantOption->low_qty = $variant['low_qty'] ?? $variant['low_qty'];
                        $VariantOption->stock_management = array_key_exists('stock_management',  $variant) ?  $variant['stock_management'] : 2;
                        $VariantOption->is_available = array_key_exists('is_available',  $variant) ?  $variant['is_available'] : 2;
                        $VariantOption->save();
                    }
                }
            }
            $extras_id = $request->extras_id;
            if ($request->has_extras == 1) {
                if ($request->extras_name != null && $request->extras_name != "") {
                    foreach ($request->extras_name as $key => $no) {
                        if (@$no != "" && @$request->extras_price[$key] != "") {
                            if (@$extras_id[$key] == "") {
                                $extras = new Extra();
                                $extras->item_id = $product->id;
                                $extras->name = $no;
                                $extras->price = $request->extras_price[$key];
                                $extras->save();
                            } else if (@$extras_id[$key] != "") {
                                Extra::where('id', @$extras_id[$key])->update(['name' => $request->extras_name[$key], 'price' => $request->extras_price[$key]]);
                            }
                        }
                    }
                }
            } else {
                Extra::where('item_id', $product->id)->delete();
            }
            return redirect('admin/products')->with('success', trans('messages.success'));
        } catch (\Throwable $th) {

            return redirect()->back()->with('error', trans('messages.wrong'));
        }
    }


    public function update_image(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_image' => 'image|max:' . helper::imagesize() . '|' . helper::imageext(),
        ], [
            'product_image.max' => trans('messages.image_size_message'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', trans('messages.image_size_message') . ' ' . helper::appdata('')->image_size . ' ' . 'MB');
        } else {
            $itemimage = ProductImage::where('id', $request->id)->first();
            $carts = Cart::where('item_id', $itemimage->item_id)->delete();
            if ($request->has('product_image')) {
                if ($itemimage->is_imported == 2) {
                    if ($itemimage->image != "" && $itemimage->image != null && file_exists(storage_path('app/public/item/' . $itemimage->image))) {
                        unlink(storage_path('app/public/item/' . $itemimage->image));
                    }
                }
                // $imgname = helper::imageresize($request->file('product_image'), storage_path('app/public/item'));
                $reimage = 'item-' . uniqid() . "." . $request->file('product_image')->getClientOriginalExtension();
                $request->file('product_image')->move(storage_path('app/public/item/'), $reimage);
                ProductImage::where('id', $request->id)->update(['image' => $reimage]);
                return redirect()->back()->with('success', trans('messages.success'));
            } else {
                return redirect()->back()->with('error', trans('messages.wrong'));
            }
        }
    }
    public function delete_variation(Request $request)
    {
        $checkvariationcount = Variants::where('item_id', $request->product_id)->count();
        $Updatevariants = Variants::where('id', $request->id)->first();
        Cart::where('item_id', $Updatevariants->item_id)->delete();
        $Updatevariants->delete();
        return redirect()->back()->with('success', trans('messages.success'));
    }
    public function delete_extras(Request $request)
    {
        $deletedata = Extra::where('id', $request->id)->first();
        Cart::where('item_id', $deletedata->item_id)->delete();
        $deletedata->delete();
        if ($deletedata) {
            return redirect()->back()->with('success', trans('messages.success'));
        } else {
            return redirect()->back()->with('error', trans('messages.wrong'));
        }
    }
    public function status($slug, $status)
    {
        try {
            $checkproduct = Item::where('slug', $slug)->first();
            $checkproduct->is_available = $status;
            $checkproduct->save();
            if ($status == 2) {
                Cart::where('item_id', $checkproduct->id)->delete();
            }
            return redirect('admin/products')->with('success', trans('messages.success'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', trans('messages.wrong'));
        }
    }
    public function delete_product($slug)
    {
        try {
            $checkproduct = Item::where('slug', $slug)->first();
            $checkvariation = Variants::where('item_id', $checkproduct->id)->delete();
            $checkextras = Extra::where('item_id', $checkproduct->id)->delete();
            $productimage = ProductImage::where('item_id', $checkproduct->id)->get();
            $getbanner = Banner::where('product_id', $checkproduct->id)->where('vendor_id', $checkproduct->vendor_id)->get();
            $gettestimonials = testimonials::where('item_id', $checkproduct->id)->where('vendor_id', $checkproduct->vendor_id)->delete();
            foreach ($getbanner as $banner) {
                $banner->type = "";
                $banner->product_id = "";
                $banner->update();
            }
            foreach ($productimage as $image) {
                if ($image->is_imported == 2) {
                    if ($image->image != "" && $image->image != null && file_exists(storage_path('app/public/item/' . $image->image))) {
                        unlink(storage_path('app/public/item/' . $image->image));
                    }
                }
                $image->delete();
            }
            Cart::where('item_id', $checkproduct->id)->delete();
            $checkproduct->delete();

            // if ($checkproduct->is_imported == 2) {
            //     foreach ($productimage as $image) {
            //         if ($image->image != "" && $image->image != null && file_exists(storage_path('app/public/item/' . $$image->image))) {
            //             unlink(storage_path('app/public/item/' . $image->image));
            //         }
            //     }
            // }
            return redirect()->back()->with('success', trans('messages.success'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', trans('messages.wrong'));
        }
    }
    public function import()
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $checkplan = helper::checkplan($vendor_id, '');
        $v = json_decode(json_encode($checkplan));
        if (@$v->original->status == 2) {
            return redirect('admin/products')->with('error', @$v->original->message);
        }
        return view('admin.import.import');
    }
    public function generatepdf()
    {
        $categorylist = Category::where('is_available', 1)->where('is_deleted', 2)->where('vendor_id', Auth::user()->id)->get();
        $pdf = PDF::loadView('admin.product.categorylist', ['categorylist' => $categorylist]);
        return $pdf->download('category.pdf');
    }
    public function reorder_category(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $getproducts = Item::where('vendor_id', $vendor_id)->get();
        foreach ($getproducts as $product) {
            foreach ($request->order as $order) {
                $product = Item::where('id', $order['id'])->first();
                $product->reorder_id = $order['position'];
                $product->save();
            }
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
    }
    public function delete_review(Request $request)
    {
        $deletereview = Testimonials::where('id', $request->id)->first();
        $deletereview->delete();
        return redirect()->back()->with('success', trans('messages.success'));
    }
    public function add_image(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        if ($request->has('image')) {
            $validator = Validator::make($request->all(), [
                'image.*' =>  'image|max:' . helper::imagesize() . '|' . helper::imageext(),
            ], [
                'image.max' => trans('messages.image_size_message'),
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with('error', trans('messages.image_size_message') . ' ' . helper::appdata('')->image_size . ' ' . 'MB');
            } else {
                Cart::where('item_id', $request->product_id)->delete();
                foreach ($request->file('image') as $file) {
                    $reimage = 'item-' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(storage_path('app/public/item/'), $reimage);
                    // $imgname = helper::imageresize($file, storage_path('app/public/item'));
                    $productimage = new ProductImage();
                    $productimage->image = $reimage;
                    $productimage->vendor_id =  $vendor_id;
                    $productimage->item_id = $request->product_id;
                    $productimage->is_imported = 2;
                    $productimage->save();
                }
            }
        }
        return redirect()->back()->with('success', trans('messages.success'));
    }
    public function delete_image($id, $item_id)
    {
        $count = ProductImage::where('item_id', $item_id)->count();
        $productdata = ProductImage::where('id', $id)->first();
        if ($count > 1) {
            if ($productdata->is_imported == 2) {
                if ($productdata->image != "" && $productdata->image != null && file_exists(storage_path('app/public/item/' . $productdata->image))) {
                    unlink(storage_path('app/public/item/' . $productdata->image));
                }
            }
            Cart::where('item_id', $productdata->item_id)->delete();
            $productdata->delete();
            return redirect()->back()->with('success', trans('messages.success'));
        } else {
            return redirect()->back()->with('msg', trans('messages.last_image'));
        }
    }

    public function getProductVariantsPossibilities(Request $request, $item_id = 0)
    {
        $variant_edit = $request->variant_edit;

        if (!empty($variant_edit) && $variant_edit == 'edit') {
            $variant_option123 = json_decode($request->hiddenVariantOptions, true);
            foreach ($variant_option123 as $key => $value) {
                $new_key = array_search($value['variant_name'], array_column($request->variant_edt, 'variant_name'));
                if (!empty($request->variant_edt[$new_key]['variant_options'])) {
                    $new_val = explode('|', $request->variant_edt[$new_key]['variant_options']);
                    $variant_option123[$key]['variant_options'] = array_merge($variant_option123[$key]['variant_options'], $new_val);
                }
            }
            $request->hiddenVariantOptions = json_encode($variant_option123);
        }

        $variant_name = $request->variant_name;
        $variant_options = $request->variant_options;
        $hiddenVariantOptions = $request->hiddenVariantOptions;
        $hiddenVariantOptions = json_decode($hiddenVariantOptions, true);
        $result = [
            'hiddenVariantOptions' => json_encode($hiddenVariantOptions),
            'message' => trans('messages.variant_attribute_exist'),
        ];
        if (!empty($hiddenVariantOptions)) {
            foreach ($hiddenVariantOptions as $key => $value) {
                if (in_array($request->variant_name, $hiddenVariantOptions[$key])) {
                    return response()->json($result);
                }
            }
        }
        $variants = [
            [
                'variant_name' => $variant_name,
                'variant_options' => explode('|', $variant_options),
            ],
        ];
        if (empty($variant_edit) && $variant_edit != 'edit') {
            $hiddenVariantOptions = array_merge($hiddenVariantOptions, $variants);
        }
        $hiddenVariantOptions = array_map("unserialize", array_unique(array_map("serialize", $hiddenVariantOptions)));
        $optionArray = $variantArray = [];
        foreach ($hiddenVariantOptions as $variant) {
            $variantArray[] = $variant['variant_name'];
            $optionArray[] = $variant['variant_options'];
        }
        $possibilities = Item::possibleVariants($optionArray);
        $variantArray = array_unique($variantArray);
        if (!empty($variant_edit) && $variant_edit == 'edit') {
            $varitantHTML = view('admin.product.variants.edit_list', compact('possibilities', 'variantArray', 'item_id'))->render();
        } else {
            $varitantHTML = view('admin.product.variants.list', compact('possibilities', 'variantArray'))->render();
        }
        $result = [
            'status' => false,
            'hiddenVariantOptions' => json_encode($hiddenVariantOptions),
            'varitantHTML' => $varitantHTML,
        ];
        return response()->json($result);
    }

    public function productVariantsEdit(Request $request, $item_id)
    {
        $product = Item::where('id', $item_id)->first();
        $productVariantOption = json_decode($product->variants_json, true);
        if (empty($productVariantOption)) {
            return view('admin.product.variants.create')->render();
        } else {
            return view('admin.product.variants.edit', compact('product', 'productVariantOption', 'item_id'))->render();
        }
    }
    public function reorder_image(Request $request)
    {

        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $getproducts = ProductImage::where('vendor_id', $vendor_id)->where('item_id', $request->item_id)->get();
        $arr = explode('|', $request->input('ids'));
        foreach ($arr as $sortOrder => $id) {
            if ($id != "" && $id != null) {
                $menu = ProductImage::find($id);
                $menu->reorder_id = $sortOrder;
                $menu->save();
            }
        }

        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
    }
    public function bulk_delete_product(Request $request)
    {
        try {
            foreach ($request->id as $id) {
                    $checkproduct = Item::where('id', $id)->first();
                    $checkvariation = Variants::where('item_id', $checkproduct->id)->delete();
                    $checkextras = Extra::where('item_id', $checkproduct->id)->delete();
                    $productimage = ProductImage::where('item_id', $checkproduct->id)->get();
                    $getbanner = Banner::where('product_id', $checkproduct->id)->where('vendor_id', $checkproduct->vendor_id)->get();
                    $gettestimonials = testimonials::where('item_id', $checkproduct->id)->where('vendor_id', $checkproduct->vendor_id)->delete();
                    foreach ($getbanner as $banner) {
                        $banner->type = "";
                        $banner->product_id = "";
                        $banner->update();
                    }
                    foreach ($productimage as $image) {
                        if ($image->is_imported == 2) {
                            if ($image->image != "" && $image->image != null && file_exists(storage_path('app/public/item/' . $image->image))) {
                                unlink(storage_path('app/public/item/' . $image->image));
                            }
                        }
                        $image->delete();
                    }
                    Cart::where('item_id', $checkproduct->id)->delete();
                    $checkproduct->delete();
            }
            return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);

        } catch (\Throwable $th) {
            return response()->json(['status' => 0, 'msg' => trans('messages.error')], 200);
        }
    }
}
