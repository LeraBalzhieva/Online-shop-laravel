<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Service\CartService;
use App\Http\Service\OrderService;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

/**
 *  Контроллер для управления заказами пользователей.
 *  Отвечает за отображение страницы оформления заказа,
 *  создание нового заказа и просмотр всех заказов.
 */
class OrderController extends Controller
{
    public function __construct(
        protected CartService  $cartService,
        protected OrderService $orderService)
    {
    }

    /**
     * Отображает страницу оформления заказа.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|object
     */
    public function getOrder()
    {
        $cartProducts = $this->cartService->getCart(Auth::user());
        return view('order', ['cartProducts' => $cartProducts]);
    }

    /**
     * Создаёт новый заказ.
     * @param OrderRequest $request Валидированный запрос с данными заказа.
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function order(OrderRequest $request)
    {
        $paymentUrl = $this->orderService->create($request);
        return redirect()->away($paymentUrl);
    }

    /**
     * Отображает список всех заказов пользователя.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|object
     */
    public function getAllOrders()
    {

        $userOrders = Order::with('orderProducts.product')->get();
        return view('order_product', ['userOrders' => $userOrders]);

    }
}
