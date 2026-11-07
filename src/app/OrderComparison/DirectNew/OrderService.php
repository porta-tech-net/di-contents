<?php

namespace App\OrderComparison\DirectNew;

class OrderService
{
    public function placeOrder(int $productId, int $quantity): array
    {
        $stock = new StockService();
        $price = new PriceService();
        $repository = new OrderRepository();

        if (! $stock->hasStock($productId, $quantity)) {
            return ['success' => false, 'message' => 'Insufficient stock'];
        }

        $total = $price->calculate($productId, $quantity);

        return ['success' => true, 'order' => $repository->save($productId, $quantity, $total)];
    }
}