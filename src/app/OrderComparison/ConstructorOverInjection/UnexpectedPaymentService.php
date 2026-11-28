<?php

namespace App\OrderComparison\ConstructorOverInjection;

use LogicException;

class UnexpectedPaymentService extends PaymentService
{
    public function charge(int $amount): array
    {
        throw new LogicException(
            'PaymentService must not be called'
        );
    }
}