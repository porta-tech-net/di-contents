<?php

namespace App\OrderComparison\ControlFreak;

use App\OrderComparison\ManualDi\StockService;

class WarehouseStockService extends StockService
{
    public function __construct(
        private WarehouseConfig $warehouseConfig
    ) {
    }

    public function hasStock(
        int $productId,
        int $quantity
    ): bool {
        $warehouseId = $this->warehouseConfig->getWarehouseId();

        // 倉庫IDが1の場合、在庫数は10個とする
        if ($warehouseId === 1) {
            return $quantity <= 10;
        }

        // その他の倉庫では在庫数を5個とする
        return $quantity <= 5;
    }
}
