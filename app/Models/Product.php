<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'image',
        'sku',
        'formula',
        'slug',
        'quantity',
        'template_name',
        'price',
        'is_public',
        'site_url'
    ];

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
