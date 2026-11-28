<?php

namespace App\OrderComparison\ConstructorOverInjection;

class FraudCheckService
{
    public function isSuspicious(
        int $productId,
        int $quantity,
        int $amount
    ): bool {
        return $amount >= 100000;
    }
}