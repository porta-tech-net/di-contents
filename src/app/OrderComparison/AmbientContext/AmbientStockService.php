<?php

namespace App\OrderComparison\AmbientContext;

use App\OrderComparison\ManualDi\StockService;

class AmbientStockService extends StockService
{
    public function hasStock(
        int $productId,
        int $quantity
    ): bool {
        $warehouseId = WarehouseContext::getWarehouseId();

        // 倉庫IDが1の場合、在庫数は10個
        if ($warehouseId === 1) {
            return $quantity <= 10;
        }

        // その他の倉庫では在庫数は5個
        return $quantity <= 5;
    }
}
