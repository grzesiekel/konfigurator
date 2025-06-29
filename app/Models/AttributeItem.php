<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeItem extends Model
{
    protected $fillable = ['attribute_id', 'value','image'];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
