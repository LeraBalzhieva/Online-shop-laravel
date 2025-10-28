<?php

namespace App\DTO;

class YooKassaWebhookDTO
{
    public string $event;
    public int $orderId;
    public function __construct(array $data)
    {
        $this->event = $data['event'] ?? '';
        $this->orderId = $data['object']['metadata']['orderId'] ?? 0;
    }
}
