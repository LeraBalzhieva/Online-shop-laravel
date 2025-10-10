<?php

namespace App\Jobs;

use App\DTO\YougileTaskDto;
use App\Http\Service\Clients\YougileClient;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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

    public function handle(): void
    {

        try {
            echo "Test";

            $client = new YougileClient();
            $response = $client->createTask($this->dto);



        } catch (\Exception $exception) {
            print_r($exception->getMessage());
        }

    }


}
