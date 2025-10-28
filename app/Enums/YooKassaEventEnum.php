<?php

namespace App\Enums;

enum YooKassaEventEnum: string
{
    case PAYMENT_SUCCEEDED = 'payment.succeeded';
    case PAYMENT_CANCELED = 'payment.canceled';
    case REFUND_SUCCEEDED = 'refund.succeeded';
}
