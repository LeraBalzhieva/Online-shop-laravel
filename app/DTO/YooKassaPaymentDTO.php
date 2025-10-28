<?php

namespace App\DTO;

use App\Enums\YooKassaEventEnum;

class YooKassaPaymentDTO
{
    public int $orderId;
    public int $amount;
    public string $description;
    public YooKassaEventEnum $event;

    public function __construct(int $orderId, int $amount, string $description)
    {
        $this->orderId = $orderId;
        $this->amount = $amount;
        $this->description = $description;
    }
}
