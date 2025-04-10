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
        $productId = $request->getProductId();
        $amount = $request->getQuantity();

        $result = $this->cartService->addProduct($productId, $amount);

        return json_encode(['cart' => $result]);
    }

    public function decreaseProductFromCart(DecreaseProductRequest $request)
    {
        $productId = $request->getProductId();
        $amount = $request->getQuantity();

        $result = $this->cartService->decreaseProduct($productId, $amount);

        return json_encode(['cart' => $result]);
    }
}
