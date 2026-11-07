<?php

namespace App\OrderComparison\ContainerInterface;

interface PriceServiceInterface
{
    public function calculate(
        int $productId,
        int $quantity
    ): array;
}
