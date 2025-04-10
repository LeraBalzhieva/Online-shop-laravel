<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\UserProduct;
use Illuminate\Support\Facades\Auth;

class ProductController
{
    public function getCatalog()
    {
        $products = Product::all();
        $cartItems = UserProduct::query()->where('user_id', Auth::id())->get();

        return view('catalog', compact('products'));
    }

}
