<?php

namespace App\OrderComparison\ManualDi;

class StockService
{
    private array $stocks = [1001 => 10];

    public function hasStock(int $productId, int $quantity): bool
    {
        return $quantity > 0 && ($this->stocks[$productId] ?? 0) >= $quantity;
    }
}
