<?php

namespace App\OrderComparison\ContainerInterface;

class FakeStockService implements StockServiceInterface
{
    public function hasStock(int $productId, int $quantity): bool
    {
        return false;
    }
}