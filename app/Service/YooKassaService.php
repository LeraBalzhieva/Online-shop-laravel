<?php

namespace App\Service;

use App\DTO\YooKassaPaymentDTO;
use App\DTO\YooKassaWebhookDTO;
use App\Enums\OrderStatusEnum;
use App\Enums\YooKassaEventEnum;
use App\Models\Order;
use YooKassa\Client;

/**
 * Сервис для работы с платёжной системой YooKassa.
 *
 * Отвечает за создание платежей и обработку вебхуков, поступающих от YooKassa.
 */
class YooKassaService
{
    public Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->client->setAuth(
            config('services.yookassa.shop_id'),
            config('services.yookassa.api_key')
        );
    }

    /**
     *  Создаёт новый платёж в YooKassa и возвращает URL для перехода на страницу оплаты.
     * @param YooKassaPaymentDTO $dto Объект DTO с параметрами платежа (сумма, описание, ID заказа).
     * @return string URL страницы подтверждения оплаты (redirect-ссылка).
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
    public function createPayment(YooKassaPaymentDTO $dto)
    {
        $payment = $this->client->createPayment([
            'amount' => [
                'value' => number_format($dto->amount, 2, '.', ''),
                'currency' => 'RUB'
            ],
            'confirmation' => [
                'type' => 'redirect',
                'return_url' => route('payment.success', ['order_id' => $dto->orderId]),
            ],
            'capture' => true,
            'description' => $dto->description,
            'metadata' => [
                'orderId' => $dto->orderId,
            ],
        ], uniqid('', true));

        return $payment->getConfirmation()->getConfirmationUrl();
    }

    /**
     *  Обрабатывает входящий вебхук от YooKassa.
     * @param array $data Данные, полученные от YooKassa (JSON-payload webhook-уведомления).
     * @return \Illuminate\Http\JsonResponse Ответ, подтверждающий успешную обработку webhook.
     */

    public function handleWebhook(YooKassaWebhookDTO $dto): void
    {
        if ($dto->event === YooKassaEventEnum::PAYMENT_SUCCEEDED) {
            $order = Order::find($dto->orderId);

            if ($order && $order->status !== OrderStatusEnum::PAID) {
                $order->update(['status' => OrderStatusEnum::PAID]);
            }
        }
    }
}
