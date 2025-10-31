<?php

namespace App\Http\Controllers;

use App\DTO\CreateOrderDTO;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Service\CartService;
use App\Service\OrderService;
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
        $dto = new CreateOrderDTO(
            name: $request->input('name'),
            phone: $request->input('phone'),
            city: $request->input('city'),
            address: $request->input('address'),
            comment: $request->input('comment'),
            user: Auth::user(),
        );

        $order = $this->orderService->createOrder($dto);

        $paymentUrl = $this->orderService->createYougileTask($order);

        return redirect($paymentUrl);
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
