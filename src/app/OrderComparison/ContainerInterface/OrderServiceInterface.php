<?php

namespace App\OrderComparison\ContainerInterface;

interface OrderServiceInterface
{
    public function placeOrder(
        int $productId,
        int $quantity,
        string $orderType = 'normal'
    ): array;
}
