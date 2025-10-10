<?php

namespace App\Http\Service;

use App\DTO\YougileTaskDto;
use App\Http\Requests\OrderRequest;
use App\Jobs\CreateTaskYougile;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\UserProduct;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{

    protected CartService $cartService;
    private $order;

    public function __construct()
    {
        $this->cartService = new CartService();
        $this->order = new Order();
    }

    public function create(OrderRequest $request)
    {
        $cartProducts = UserProduct::query()->where('user_id', $request->user()->id)->get();

        try {
            $order = DB::transaction(function () use ($request, $cartProducts) {
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

                $userProducts = UserProduct::query()->where('user_id', $request->user()->id)->delete();

                return $order;
             });

            $description =  "Имя: {$this->order->name} <br>"
                . "Адрес: {$this->order->address} <br>"
                . "Телефон: {$this->order->phone} <br>"
                . "Список товаров: <br>";

            $orderProducts = $order->orderProducts()->get();
            foreach ($orderProducts as $orderProduct) {
                $description .= $orderProduct->product->name . ", "
                    . $orderProduct->amount . ", ";
            }

            $dto = new YougileTaskDto($order->id, $description);

            CreateTaskYougile::dispatch($dto, $order->id);

        } catch (\Throwable $exception) {
            throw $exception;
        }


    }



}
