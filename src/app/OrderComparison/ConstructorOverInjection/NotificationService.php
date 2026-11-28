<?php

namespace App\OrderComparison\ConstructorOverInjection;

class NotificationService
{
    public function notify(
        int $productId,
        int $quantity
    ): array {
        return [
            'success' => true,
            'event' => 'order_completed',
            'product_id' => $productId,
            'quantity' => $quantity,
            'status' => 'sent',
        ];
    }
}