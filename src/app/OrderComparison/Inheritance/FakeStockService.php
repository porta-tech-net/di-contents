<?php

namespace App\OrderComparison\Inheritance;

class FakeStockService extends StockService
{
    public function hasStock(int $productId, int $quantity): bool
    {
        return false;
    }
}