<?php

namespace App\OrderComparison\ControlFreak;

use App\OrderComparison\ManualDi\StockService;

class TestStockService extends StockService
{
    public function hasStock(
        int $productId,
        int $quantity
    ): bool {
        return true;
    }
}
