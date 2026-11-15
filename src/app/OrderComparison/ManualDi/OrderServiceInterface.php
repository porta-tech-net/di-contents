<?php

namespace App\OrderComparison\ManualDi;

interface OrderServiceInterface
{
    public function placeOrder(
        int $productId,
        int $quantity,
        string $orderType = 'normal'
    ): array;
}
