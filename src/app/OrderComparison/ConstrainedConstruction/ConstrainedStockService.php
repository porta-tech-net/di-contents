<?php

namespace App\OrderComparison\ConstrainedConstruction;

use App\OrderComparison\ControlFreak\WarehouseConfig;
use App\OrderComparison\ControlFreak\WarehouseStockService;
use App\OrderComparison\ManualDi\StockService;
use LogicException;

class ConstrainedStockService extends StockService
{
    private ?WarehouseStockService $stockService = null;

    public function setWarehouse(int $warehouseId): void
    {
        $this->ensureNotConfigured();

        $config = new WarehouseConfig($warehouseId);

        $this->stockService = new WarehouseStockService(
            $config
        );
    }

    public function setStockService(
        StockService $stockService
    ): void {
        $this->ensureNotConfigured();

        $this->stockService = $stockService;
    }

    private function ensureNotConfigured(): void
    {
        if ($this->stockService !== null) {
            throw new LogicException(
                'Stock service is already configured'
            );
        }
    }

    public function hasStock(
        int $productId,
        int $quantity
    ): bool {
        if ($this->stockService === null) {
            throw new LogicException(
                'Warehouse is not configured'
            );
        }

        return $this->stockService->hasStock(
            $productId,
            $quantity
        );
    }
}
