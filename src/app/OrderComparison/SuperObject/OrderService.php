<?php

namespace App\OrderComparison\SuperObject;

class OrderService
{
    public function __construct(private SuperObject $app)
    {
    }

    public function placeOrder(int $productId, int $quantity): array
    {
        if (! $this->app->stockService->hasStock($productId, $quantity)) {
            return ['success' => false, 'message' => 'Insufficient stock'];
        }

        $total = $this->app->priceService->calculate($productId, $quantity);

        return ['success' => true, 'order' => $this->app->orderRepository->save($productId, $quantity, $total)];
    }
}
