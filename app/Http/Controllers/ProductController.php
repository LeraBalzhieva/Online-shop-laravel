<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController
{

    public function getCatalog()
    {
        $products = Product::all();
        return view('catalog', compact('products'));
    }
  /*  public function addToCart($id)
    {
        // Логика добавления товара в корзину
        // Например, можно использовать сессии для хранения корзины
        $product = Product::find($id);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "price" => $product->price,
                "quantity" => 1
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('catalog')->with('success', 'Товар добавлен в корзину!');
    }

    public function removeFromCart($id)
    {
        // Логика удаления товара из корзины
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            return redirect()->route('catalog')->with('success', 'Товар удален из корзины!');
        }

        return redirect()->route('catalog')->with('error', 'Товар не найден в корзине!');
    }*/
}
