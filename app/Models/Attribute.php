<?php

namespace App\Models;
use App\Enums\AttributeType;


use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{

    protected $fillable = ['name', 'type','display_name','short_name','unit','order','min','max'];

    protected $casts = [
        'type' => AttributeType::class,
    ];
}
