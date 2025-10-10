<?php

namespace App\Http\Service\Clients;

use App\DTO\YougileTaskDto;
use App\Jobs\CreateTaskYougile;
use App\Models\Order;
use GuzzleHttp\Exception\RequestException;
use http\Exception\RuntimeException;
use Illuminate\Support\Facades\Http;

class YougileClient
{
    public string $baseUrl;
    public string $apiKey;
    public string $apiToken;
    public function __construct()
    {
        $this->baseUrl = config('services.yougile.base_url');
        $this->apiKey = config('services.yougile.api_key');
        $this->apiToken = config('services.yougile.api_token');
    }
    public function createTask(YougileTaskDto $dto): array
    {
        try {
            $response= Http::withHeaders([
                'Authorization' => 'Bearer '. $this->apiToken,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl.'/tasks', $dto->handle());

            if (!$response->successful()) {
                \Log::error('YouGile create task failed', ['response' => $response->body()]);
                throw new \RuntimeException('Ошибка при создании задачи в YouGile'  . $response->body());
            }
            return $response->json();
        } catch (RequestException $exception) {
            throw new \RuntimeException($exception->getMessage(), 500);
        }
    }
    public function deleteTask(string $taskId)
    {
        $response = retry(3, function () use ($taskId) {
            return Http::withHeaders([

                'Authorization' => 'Bearer '. $this->apiKey,
                'Content-Type' => 'application/json',
            ])->delete($this->baseUrl . '/tasks/' . $taskId);
        });

    }


}
