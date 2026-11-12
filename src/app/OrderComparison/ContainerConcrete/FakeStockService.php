<?php

namespace App\OrderComparison\ContainerConcrete;

class FakeStockService extends StockService
{
    public function hasStock(int $productId, int $quantity): bool
    {
        return false;
    }
}