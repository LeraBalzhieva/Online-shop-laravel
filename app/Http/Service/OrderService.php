<?php

namespace App\Http\Service;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\UserProduct;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
    private CartService $cartService;

    public function __construct()
    {
        $this->cartService = new CartService();
    }

    public function create(OrderRequest $request)
    {

        $cartProducts = UserProduct::query()->where('user_id', $request->user()->id)->get();

        DB::transaction(function () use ($request, $cartProducts) {
            $order = Order::query()->create([
                'user_id' => $request->user()->id,
                'name' => $request['name'],
                'phone' => $request['phone'],
                'city' => $request['city'],
                'address' => $request['address'],
                'comment' => $request['comment'],
            ]);

            foreach ($cartProducts as $cartProduct) {
                OrderProduct::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $cartProduct->product_id,
                    'amount' => $cartProduct->amount,
                ]);
            }
            UserProduct::query()->where('user_id', $request->user()->id)->delete();
        });
    }


}
