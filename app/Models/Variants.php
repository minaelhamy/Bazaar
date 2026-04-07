<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variants extends Model
{
    use HasFactory;
    protected $table = 'variants';
    protected $fillable = ['item_id','name','price','qty','original_price','min_order','max_order','low_qty','stck_management'];


    public static function variant_name($name = '', $counter = 0, $item_id = 0)
    {
        $retuen_name['has_variant'] = 0;
        $retuen_name['id'] = 'verians['.$counter.'][id]';
        $retuen_name['id_val'] = 0;
        $retuen_name['item_id'] = 'verians['.$counter.'][item_id]';
        $retuen_name['item_id_val'] = 0;
        $retuen_name['name'] = 'verians['.$counter.'][name][]';
        $retuen_name['has_name'][0] = 'verians['.$counter.'][name]';
        $retuen_name['price'] = 'verians['.$counter.'][price]';
        $retuen_name['qty'] = 'verians['.$counter.'][qty]';
        $retuen_name['price_val'] = 0;
        $retuen_name['qty_val'] = 0;
        $retuen_name['original_price'] = 'verians['.$counter.'][original_price]';
        $retuen_name['original_price_val'] = 0;
        $retuen_name['min_order'] = 'verians['.$counter.'][min_order]';
        $retuen_name['min_order_val'] = 0;
        $retuen_name['max_order'] = 'verians['.$counter.'][max_order]';
        $retuen_name['max_order_val'] = 0;
        $retuen_name['low_qty'] = 'verians['.$counter.'][low_qty]';
        $retuen_name['low_qty_val'] = 0;
        $retuen_name['stock_management'] = 'verians['.$counter.'][stock_management]';
        $retuen_name['stock_management_val'] = 0;
        $retuen_name['is_available'] = 'verians['.$counter.'][is_available]';
        $retuen_name['is_available_val'] = 0;

        if(!empty($name)) {
            $ProductVariantOption = Variants::where('name', $name)->where('item_id', $item_id)->first();
            if(!empty($ProductVariantOption)) {
                foreach(explode('|', $name) as $key => $values) {
                    $retuen_name['has_name'][$key] = 'verians['.$ProductVariantOption->id.'][variants]['.$key.'][]';
                }
                $retuen_name['id_val'] = $ProductVariantOption->id;
                $retuen_name['item_id_val'] = $ProductVariantOption->item_id;
                $retuen_name['price_val'] = $ProductVariantOption->price;
                $retuen_name['qty_val'] = $ProductVariantOption->qty;
                $retuen_name['original_price_val'] = $ProductVariantOption->original_price;
                $retuen_name['min_order_val'] = $ProductVariantOption->min_order;
                $retuen_name['max_order_val'] = $ProductVariantOption->max_order;
                $retuen_name['low_qty_val'] = $ProductVariantOption->low_qty;
                $retuen_name['stock_management_val'] = $ProductVariantOption->stock_management;
                $retuen_name['is_available_val'] = $ProductVariantOption->is_available;
                $retuen_name['price'] = 'verians['.$ProductVariantOption->id.'][price]';
                $retuen_name['original_price'] = 'verians['.$ProductVariantOption->id.'][original_price]';
                $retuen_name['min_order'] = 'verians['.$ProductVariantOption->id.'][min_order]';
                $retuen_name['max_order'] = 'verians['.$ProductVariantOption->id.'][max_order]';
                $retuen_name['low_qty'] = 'verians['.$ProductVariantOption->id.'][low_qty]';
                $retuen_name['stock_management'] = 'verians['.$ProductVariantOption->id.'][stock_management]';
                $retuen_name['is_available'] = 'verians['.$ProductVariantOption->id.'][is_available]';
                $retuen_name['qty'] = 'verians['.$ProductVariantOption->id.'][qty]';
                $retuen_name['has_variant'] = 1;
            }
        }
        return $retuen_name;
    }

    public function getproductbyvariantId($id){

        return Item::find($id)->variants_json;
    }
}
