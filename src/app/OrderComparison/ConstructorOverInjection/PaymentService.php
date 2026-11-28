<?php

namespace App\OrderComparison\ConstructorOverInjection;

class PaymentService
{
    public function charge(int $amount): array
    {
        return [
            'success' => true,
            'amount' => $amount,
            'status' => 'paid',
        ];
    }
}