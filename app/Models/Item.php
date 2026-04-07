<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Item extends Model
{
    use HasFactory;
    protected $table = 'items';
    protected $fillable = ['vendor_id','cat_id','item_name','item_price','slug','item_original_price','tax','description','image','video_url','sku','attchment_name','attchment_file','video_url','stock_management','min_order','max_order','low_qty','qty'];
    public function extras()
    {
        return $this->hasMany('App\Models\Extra', 'item_id', 'id')->select('id', 'name', 'price', 'item_id');
    }
    public function variation(){
        return $this->hasMany('App\Models\Variants','item_id','id')->select('id','item_id','name','price','original_price','qty','min_order','max_order','is_available','stock_management');
    }
    public function category_info()
    {
        return $this->hasOne('App\Models\Category', 'id', 'cat_id');
    }
    public function product_image(){
        return $this->hasOne('App\Models\ProductImage','item_id','id')->select('*',\DB::raw("CONCAT('".url('/storage/app/public/item/')."/', image) AS image_url"))->orderBy('reorder_id');
    }
    public function multi_image(){
        return $this->hasMany('App\Models\ProductImage','item_id','id')->select('*',\DB::raw("CONCAT('".url('/storage/app/public/item/')."/', image) AS image_url"))->orderBy('reorder_id');
    }
    public static function possibleVariants($groups, $prefix = '')
    {
        $result = [];
        $group  = array_shift($groups);
        foreach($group as $selected)
        {
            if($groups)
            {
                $result = array_merge($result, self::possibleVariants($groups, $prefix . $selected . '|'));
            }
            else
            {
                $result[] = $prefix . $selected;
            }
        }
        return $result;
    }
}