<?php

namespace App\OrderComparison\ManualDi;

class OrderRepository
{
    private array $orders = [];

    public function save(int $productId, int $quantity, int $total): array
    {
        $order = ['product_id' => $productId, 'quantity' => $quantity, 'total' => $total];
        $this->orders[] = $order;

        return $order;
    }
}
