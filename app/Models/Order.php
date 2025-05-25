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
        'source' => 'string',
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

        // Lista dozwolonych liter (A-Z bez I i O)
        $allowedLetters = array_diff(range('A', 'Z'), ['I', 'O']);
        $allowedLetters = array_values($allowedLetters); // Reset indeksów
        $letterIndex = ($nextNumber - 1) % count($allowedLetters);
        $letter = $allowedLetters[$letterIndex];

        return $year . $month . $letter . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
