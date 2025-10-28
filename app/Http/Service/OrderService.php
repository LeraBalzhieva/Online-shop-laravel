<?php

namespace App\Http\Service;

use App\DTO\YooKassaPaymentDTO;
use App\DTO\YougileTaskDto;
use App\Http\Requests\OrderRequest;
use App\Http\Service\Clients\YooKassaService;
use App\Jobs\CreateTaskYougile;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\UserProduct;
use Illuminate\Support\Facades\DB;

/**
 * Сервис для работы с заказами пользователей.
 */
class OrderService
{
    public function __construct(
        protected CartService     $cartService,
        protected YooKassaService $yooKassaService,
        protected Order           $order
    ) {}

    /**
     *  Создаёт заказ, формирует задачу для Yougile и возвращает URL для оплаты.
     * @param OrderRequest $request
     * @return string URL для перехода на оплату
     * @throws \Throwable
     * @throws \YooKassa\Common\Exceptions\ApiConnectionException
     * @throws \YooKassa\Common\Exceptions\ApiException
     * @throws \YooKassa\Common\Exceptions\AuthorizeException
     * @throws \YooKassa\Common\Exceptions\BadApiRequestException
     * @throws \YooKassa\Common\Exceptions\ExtensionNotFoundException
     * @throws \YooKassa\Common\Exceptions\ForbiddenException
     * @throws \YooKassa\Common\Exceptions\InternalServerError
     * @throws \YooKassa\Common\Exceptions\NotFoundException
     * @throws \YooKassa\Common\Exceptions\ResponseProcessingException
     * @throws \YooKassa\Common\Exceptions\TooManyRequestsException
     * @throws \YooKassa\Common\Exceptions\UnauthorizedException
     */
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

            $description = "Имя: {$this->order->name} <br>"
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


            $totalAmount = $order->orderProducts()->with('product')->get()
                ->sum(fn($item) => $item->amount * $item->product->price);

            $paymentDescription = "Оплата заказа № {$order->id} , сумма {$totalAmount} руб.";

            $dto = new YooKassaPaymentDTO(
                orderId: $order->id,
                amount: $totalAmount,
                description: $paymentDescription,
            );

            $paymentUrl = $this->yooKassaService->createPayment($dto);

            $order->update(['payment_url' => $paymentUrl]);

            return $paymentUrl;

        } catch (\Throwable $exception) {
            throw $exception;
        }
    }
}
