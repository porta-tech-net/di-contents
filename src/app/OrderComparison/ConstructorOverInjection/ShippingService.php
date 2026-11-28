<?php

namespace App\OrderComparison\ConstructorOverInjection;

class ShippingService
{
    public function arrange(
        int $productId,
        int $quantity
    ): array {
        return [
            'success' => true,
            'product_id' => $productId,
            'quantity' => $quantity,
            'status' => 'arranged',
        ];
    }
}