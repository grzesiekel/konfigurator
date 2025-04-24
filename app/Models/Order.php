<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'number',
        'name',
        'email',
        'phone',
        'status',
        'cart',
        'product_id',
        'notes'
    ];

    public function getRouteKeyName()
    {
        return 'number';
    }

    protected $casts = [
        'status' => 'string',
        'source'=>'string',
        'cart' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->number = static::generateOrderNumber();
        });
    }

    public static function generateOrderNumber()
    {
        $year = now()->format('y'); // Użyj tylko dwóch ostatnich cyfr roku
        $month = now()->format('m');
        $latestOrder = static::whereYear('created_at', now()->year)
            ->whereMonth('created_at', $month)
            ->latest('id')
            ->first();

        $latestNumber = $latestOrder ? intval(substr($latestOrder->number, -3)) : 0;
        $nextNumber = $latestNumber + 1;

        $letters = chr(65 + ($nextNumber - 1) % 26); // Generuj literę od A do Z

        return $year . $month . $letters . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
