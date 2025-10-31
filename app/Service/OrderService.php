<?php

namespace App\Service;

use App\DTO\CreateOrderDTO;
use App\DTO\YooKassaPaymentDTO;
use App\DTO\YougileTaskDto;
use App\Enums\OrderStatusEnum;
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
    )
    {}

    /**
     * Создает заказ
     * @param CreateOrderDTO $dto объект dto данными заказа
     * @return Order созданный заказ
     */
    public function createOrder(CreateOrderDTO $dto): Order
    {
        $cartProducts = UserProduct::query()->where('user_id', $dto->user->id)->get();
        $order = DB::transaction(function () use ($dto, $cartProducts) {
            $order = Order::query()->create([
                'user_id' => $dto->user->id,
                'name' => $dto->name,
                'phone' => $dto->phone,
                'city' => $dto->city,
                'address' => $dto->address,
                'comment' => $dto->comment,
            ]);

            foreach ($cartProducts as $cartProduct) {
                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $cartProduct->product_id,
                    'amount' => $cartProduct->amount,
                ]);
            }
            UserProduct::where('user_id', $dto->user->id)->delete();

            return $order;
        });

        return $order;
    }

    /**
     * создает Задачу на доске Yougile
     * @param Order $order
     * @return string
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
    public function createYougileTask(Order $order)
    {
        $description = "Имя: {$order->name}\n"
            . "Адрес: {$order->address}\n"
            . "Телефон: {$order->phone}\n"
            . "Товары:\n";

        foreach ($order->orderProducts()->with('product')->get() as $orderProduct) {
            $description .= "- {$orderProduct->product->name} ({$orderProduct->amount})\n";
        }

        CreateTaskYougile::dispatch(
            new YougileTaskDto($order->id, $description),
            $order->id
        );

        $totalAmount = $order->orderProducts()
            ->with('product')
            ->get()
            ->sum(fn($item) => $item->amount * $item->product->price);

        $dto = new YooKassaPaymentDTO(
            orderId: $order->id,
            amount: $totalAmount,
            description: "Оплата заказа № {$order->id}, сумма {$totalAmount} руб."
        );

        $paymentUrl = $this->yooKassaService->createPayment($dto);

        $order->update([
            'payment_url' => $paymentUrl,
            'status'=>OrderStatusEnum::PAID,
            ]);

        return $paymentUrl;

    }
}
