<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductRequest;
use App\Http\Requests\DecreaseProductRequest;
use App\Service\CartService;
use Illuminate\Support\Facades\Auth;

/**
 * Класс для управления корзиной пользователя
 */
class CartController
{

    public function __construct(protected CartService $cartService)
    {}

    /**
     * Отображает корзину пользователя
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|object
     *
     */
    public function cart()
    {

        $cartProducts = $this->cartService->getCart(Auth::user());

        return view('cart', compact('cartProducts'));
    }

    /**
     *  Добавляет товар в корзину пользователя.
     * @param AddProductRequest $request Запрос с параметрами product_id и amount
     * @return false|string JSON-ответ с обновлённым состоянием корзины
     */
    public function addProductToCart(AddProductRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        $productId = $request->input('product_id');
        $amount = $request->input('amount');

        $result = $this->cartService->addProduct($user, $productId, $amount);

        return response()->json(['cart' => $result]);
    }

    /**
     *  Уменьшает количество товара в корзине пользователя.
     * @param DecreaseProductRequest $request Запрос с параметрами product_id и amount
     * @return false|string JSON-ответ с обновлённым состоянием корзины
     * @throws \Exception
     */

    public function decreaseProductFromCart(DecreaseProductRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        $productId = $request->input('product_id');
        $amount = $request->input('amount');

        $result = $this->cartService->decreaseProduct($user, $productId, $amount);

        return response()->json(['cart' => $result]);
    }
}
