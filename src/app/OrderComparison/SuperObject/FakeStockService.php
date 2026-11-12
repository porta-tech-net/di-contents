<?php

namespace App\OrderComparison\SuperObject;

class FakeStockService extends StockService
{
    public function hasStock(int $productId, int $quantity): bool
    {
        return false;
    }
}