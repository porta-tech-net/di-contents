<?php

namespace App\OrderComparison\AmbientContext;

use App\OrderComparison\ManualDi\StockService;

class InjectedScopedStockService extends StockService
{
    public function __construct(
        private ScopedWarehouseContext $context
    ) {
    }

    public function hasStock(
        int $productId,
        int $quantity
    ): bool {
        $warehouseId = $this->context->getWarehouseId();

        if ($warehouseId === 1) {
            return $quantity <= 10;
        }

        return $quantity <= 5;
    }
}
