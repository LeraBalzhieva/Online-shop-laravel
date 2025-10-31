<?php

namespace App\Http\Controllers;

use App\DTO\YooKassaWebhookDTO;
use App\Service\YooKassaService;
use Illuminate\Http\Request;

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
