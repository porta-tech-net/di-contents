<?php

namespace App\OrderComparison\InheritanceInterface;

class FakeStockService extends StockService
{
    public function hasStock(int $productId, int $quantity): bool
    {
        return false;
    }
}