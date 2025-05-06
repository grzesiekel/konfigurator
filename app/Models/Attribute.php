<?php

namespace App\Models;
use App\Enums\AttributeType;


use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{

    protected $fillable = ['name', 'type','display_name','short_name','unit','order','min','max'];

    public function items()
    {
        return $this->hasMany(AttributeItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    // protected $casts = [
    //     'type' => AttributeType::class,
    // ];
}
