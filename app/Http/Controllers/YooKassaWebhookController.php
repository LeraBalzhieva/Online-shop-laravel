<?php

namespace App\Http\Controllers;

use App\DTO\YooKassaWebhookDTO;
use App\Http\Service\Clients\YooKassaService;
use App\Http\Service\OrderService;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 *  Контроллер для обработки вебхуков от YooKassa.
 */
class YooKassaWebhookController extends Controller
{
    public function __construct(protected YooKassaService $yooKassaService)
    {}

    /**
     *  Обрабатывает входящий вебхук от YooKassa.
     * @param Request $request HTTP-запрос, содержащий JSON-данные от YooKassa.
     * @return \Illuminate\Http\JsonResponse Ответ с подтверждением обработки.
     */
    public function handle(Request $request)
    {
        $dto = new YooKassaWebhookDto($request->all());
        $this->yooKassaService->handleWebhook($dto);
        return response()->json(['status' => 'ok']);
    }


}
