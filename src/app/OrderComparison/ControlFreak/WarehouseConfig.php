<?php

namespace App\OrderComparison\ControlFreak;

class WarehouseConfig
{
    public function __construct(
        private int $warehouseId
    ) {
    }

    public function getWarehouseId(): int
    {
        return $this->warehouseId;
    }
}
