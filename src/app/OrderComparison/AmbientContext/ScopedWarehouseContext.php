<?php

namespace App\OrderComparison\AmbientContext;

class ScopedWarehouseContext
{
    private int $warehouseId = 1;

    public function setWarehouseId(int $warehouseId): void
    {
        $this->warehouseId = $warehouseId;
    }

    public function getWarehouseId(): int
    {
        return $this->warehouseId;
    }
}
