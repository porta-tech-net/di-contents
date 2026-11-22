<?php

namespace App\OrderComparison\ServiceLocator;

class AuditLogService
{
    public function record(
        int $productId,
        int $quantity
    ): array {
        return [
            'event' => 'order_created',
            'product_id' => $productId,
            'quantity' => $quantity,
            'recorded' => true,
        ];
    }
}
