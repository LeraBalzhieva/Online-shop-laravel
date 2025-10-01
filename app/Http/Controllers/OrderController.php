<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Service\CartService;
use App\Http\Service\OrderService;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    private CartService $cartService;
    private OrderService $orderService;

    public function __construct()
    {
        $this->cartService = new CartService();
        $this->orderService = new OrderService();
    }
    public function getOrder()
    {
        $cartProducts = $this->cartService->getCart(Auth::user());
        return view('order', ['cartProducts' => $cartProducts]);
    }

    public function order(OrderRequest $request)
    {
        $this->orderService->create($request);
        return redirect()->route('order');
    }

    public function getAllOrders()
    {

        $userOrders = Order::with('orderProducts.product')->get();
        return view('order_product', ['userOrders' => $userOrders]);

    }
}
