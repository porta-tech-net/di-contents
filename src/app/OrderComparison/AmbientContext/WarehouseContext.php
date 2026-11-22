<?php

namespace App\OrderComparison\AmbientContext;

class WarehouseContext
{
    private static int $warehouseId = 1;

    public static function setWarehouseId(int $warehouseId): void
    {
        self::$warehouseId = $warehouseId;
    }

    public static function getWarehouseId(): int
    {
        return self::$warehouseId;
    }
}
