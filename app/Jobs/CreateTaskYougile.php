<?php

namespace App\Jobs;

use App\DTO\YougileTaskDto;
use App\Models\Order;
use App\Service\Clients\YougileClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Job для создания задачи в Yougile.
 * Отправляет данные заказа и сохраняет ID задачи в таблицу orders.
 */
class CreateTaskYougile implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private YougileTaskDto $dto;
    private int $orderId;

    public function __construct(YougileTaskDto $dto, int $orderId)
    {
        $this->dto = $dto;
        $this->orderId = $orderId;
    }

    /**
     * Обработчик Job
     * @return void
     */
    public function handle(): void
    {
        try {
            $client = new YougileClient();
            $response = $client->createTask($this->dto);

            if (!empty($response['id'])) {
                Order::where('id', $this->orderId)
                    ->update(['yougile_task_id' => $response['id']]);
            }

        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }

    }


}
