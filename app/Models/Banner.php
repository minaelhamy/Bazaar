<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    protected $table = 'banner_image';
    public function category_info()
    {
        return $this->hasOne('App\Models\Category','id','category_id');
    }
    public function product_info()
    {
        return $this->hasOne('App\Models\Item','id','product_id');
    }
}
