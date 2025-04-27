<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Product $product)
    {
        if (!$product->is_public && !auth()->check()) {
            abort(403, 'Dostęp tylko dla zalogowanych użytkowników');
        }

        $attributes = Attribute::all();
        $pageData = json_encode([
            'formula' => $product->formula,
            'availableFields' => $attributes->pluck('name')
        ]);

        $product_template = $product->template_name;

        return view('templates.'. $product_template , compact('product', 'attributes', 'pageData'));
    }

    
}
