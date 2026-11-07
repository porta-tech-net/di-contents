<?php

namespace App\OrderComparison\InheritanceInterface;

interface OrderServiceInterface
{
    public function placeOrder(
        int $productId,
        int $quantity,
        string $orderType = 'normal'
    ): array;
}
