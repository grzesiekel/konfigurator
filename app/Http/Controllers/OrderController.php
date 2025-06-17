<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        if (!empty($request->honeypot)) {
            return redirect()->back()->withErrors(['spam' => 'Bot detected']);
        }
       
        $validatedData = $request->validate([
            'cart' => 'required|json',
           'product_id' => 'required|string',
        ]);

        $order = Order::create([
            'product_id' => $validatedData['product_id'],
            'cart' => $validatedData['cart'],
         
        ]);

        return redirect()->route('order.show', ['order' => $order]);

    }
    public function show(Order $order)
    {
        if (!$order) {
            abort(404);
        }
        
        return view('templates.summary', compact('order'));
    }

     public function print(Order $order)
    {
        $cartData = json_decode($order->cart,true);
        $order = $order;

       

        return view('admin.print', compact('cartData','order'));
    }
}
