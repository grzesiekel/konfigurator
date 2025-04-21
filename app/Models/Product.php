<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'formula',
        'slug',
        'quantity',
        'template_name',
        'price',
        'is_public',
    ];
}
