<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\ProductImage;
use App\Models\Extra;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;

class ImportProduct implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        try {
            foreach ($rows as $row) {

                $item = new Item();
                $item->cat_id = $row['category_id'];
                $item->image = 'item.png';
                $item->vendor_id = Auth::user()->id;
                $item->slug = Str::slug($row['product_name'] . ' ', '-') . '-' . Str::random(5);
                $item->item_name = $row['product_name'];
                $item->item_price = $row['selling_price'];
                $item->item_original_price = $row['original_price'];
                $item->tax = $row['tax'];
                $item->description = $row['description'];
                $item->sku = $row['sku'];
                $item->video_url = $row['video_url'];
                $item->stock_management = $row['stock_management'];
                $item->qty =  $row['stock_management'] == 1 ? $row['qty'] : 0;
                $item->min_order = $row['stock_management'] == 1 ? $row['min_order'] : 0;
                $item->max_order = $row['stock_management'] == 1 ? $row['max_order'] : 0;
                $item->low_qty = $row['stock_management'] == 1 ? $row['low_qty'] : 0;
                $item->is_imported = 1;
                $item->save();

                if ($row['image'] != "" && $row['image'] != null) {
                    $images = explode('|', $row['image']);
                    foreach ($images as $image) {
                        $productimage = new ProductImage();
                        $url =  strtok($image, '?');
                        $filename = basename($url);
                        $productimage->vendor_id = Auth::user()->id;
                        $productimage->item_id = $item->id;
                        $productimage->image = preg_replace('/\s+/', '', $filename);
                        $productimage->is_imported = 1;
                        $productimage->save();
                    }
                }
                $extra_name = explode('|', $row['extra_name']);
                $extra_price = explode('|', $row['extra_price']);
                if ($row['extra_name'] != "" && $row['extra_name'] != null) {
                    foreach ($extra_name as $key => $extraname) {
                        $extra = new Extra();
                        $extra->name = $extraname;
                        $extra->item_id = $item->id;
                        $extra->price = $extra_price[$key] == "" || $extra_price[$key] == null ? 0 : $extra_price[$key];
                        $extra->save();
                    }
                }
            }
        } catch (\Throwable $th) {
        }
    }

    public function headingRow(): int
    {
        return 1;
    }
}
