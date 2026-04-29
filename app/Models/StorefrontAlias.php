<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorefrontAlias extends Model
{
    protected $fillable = [
        'vendor_id',
        'slug',
    ];
}
