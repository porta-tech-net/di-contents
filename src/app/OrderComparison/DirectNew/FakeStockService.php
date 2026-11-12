<?php

namespace App\OrderComparison\DirectNew;

class FakeStockService extends StockService
{
    public function hasStock(int $productId, int $quantity): bool
    {
        return false;
    }
}