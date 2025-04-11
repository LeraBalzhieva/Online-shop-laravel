<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\AddProductRequest;
use App\Http\Requests\DecreaseProductRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Service\CartService;


class CartController
{
    private CartService $cartService;

    public function __construct()
    {
        $this->cartService = new CartService();
    }

    public function cart()
    {

        $cartProducts = $this->cartService->getCart(Auth::user());


        if (empty($cartProducts)) {
            return redirect()->route('catalog');
        }
        return view('cart', compact('cartProducts'));
    }

    public function addProductToCart(AddProductRequest $request)
    {
        $user = Auth::user();
        $productId = $request->input('product_id');
        $amount = $request->input('amount');

        $result = $this->cartService->addProduct($user, $productId, $amount);

        return json_encode(['cart' => $result]);
    }

    public function decreaseProductFromCart(DecreaseProductRequest $request)
    {
        $user = Auth::user();
        $productId = $request->input('product_id');
        $amount = $request->input('amount');

        $result = $this->cartService->decreaseProduct($user, $productId, $amount);

        return json_encode(['cart' => $result]);
    }
}
