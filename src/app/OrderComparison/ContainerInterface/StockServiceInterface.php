<?php

namespace App\OrderComparison\ContainerInterface;

interface StockServiceInterface
{
    public function hasStock(int $productId, int $quantity): bool;
}
