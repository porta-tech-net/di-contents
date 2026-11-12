<?php

namespace App\OrderComparison\ManualDi;

class FakeStockService extends StockService
{
    public function hasStock(int $productId, int $quantity): bool
    {
        return false;
    }
}