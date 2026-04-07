<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
      public function products()
    {
        return $this->hasOne('App\Models\Item', 'id', 'product_id');
    }
}
