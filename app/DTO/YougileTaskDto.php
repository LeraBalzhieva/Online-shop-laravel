<?php

namespace App\DTO;

class YougileTaskDto
{
    public int $orderId;
    public string $description;
    public string $columnId;
    public bool $archived;
    public bool $completed;

    public function __construct(int $orderId, string $description)
    {
        $this->orderId = $orderId;
        $this->description = $description;
        $this->columnId = 'd6b2bc74-af78-4866-901d-0e3b52f8b256';
        $this->archived = false;
        $this->completed = false;
    }

    public function handle(): array
    {
        return [
            'title' => 'заказ #' . $this->orderId,
            'description' => $this->description,
            'columnId' => $this->columnId,
            'archived' => $this->archived,
            'completed' => $this->completed,
        ];
    }
}
