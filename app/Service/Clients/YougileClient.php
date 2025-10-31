<?php

namespace App\Service\Clients;

use App\DTO\YougileTaskDto;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;

/**
 * Клиент для работы с API YouGile.
 * Отвечает за создание и удаление задач через API.
 */
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

    /**
     * Создает задачу
     * @param YougileTaskDto $dto
     * @return array
     * @throws \Illuminate\Http\Client\ConnectionException
     */
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

    /**
     * Удаляет задачу
     * @param string $taskId
     * @return void
     * @throws \Illuminate\Http\Client\ConnectionException
     * @throws \Throwable
     */
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
