<?php

namespace App\OrderComparison\Inheritance;

class OrderRepository
{
    private array $orders = [];

    public function save(
        int $productId,
        int $quantity,
        int $subtotal,
        int $tax,
        int $total
    ): array {
        $order = [
            'product_id' => $productId,
            'quantity' => $quantity,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
        ];

        $this->orders[] = $order;

        return $order;
    }
}
