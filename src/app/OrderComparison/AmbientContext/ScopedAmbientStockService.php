<?php

namespace App\OrderComparison\AmbientContext;

use App\OrderComparison\ManualDi\StockService;

class ScopedAmbientStockService extends StockService
{
    public function hasStock(
        int $productId,
        int $quantity
    ): bool {
        $context = app(ScopedWarehouseContext::class);

        $warehouseId = $context->getWarehouseId();

        if ($warehouseId === 1) {
            return $quantity <= 10;
        }

        return $quantity <= 5;
    }
}
