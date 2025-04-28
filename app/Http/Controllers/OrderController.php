<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'cart' => 'required|array',
        ]);

        $order = Order::create([
            'product_id' => $validated['product_id'],
            'cart' => json_encode($validated['cart']),
         
        ]);

        return response()->json([
            'success' => true,
            'order' => $order,
            'redirect_url' => route('order.summary', $order),
        ]);
    }
}
