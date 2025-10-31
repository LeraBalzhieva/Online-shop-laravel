<?php

namespace App\DTO;

use App\Models\User;

class CreateOrderDTO
{
    public function __construct(
        public string $name,
        public string $phone,
        public string $city,
        public string $address,
        public ?string $comment,
        public User $user,
    ) {}
}

