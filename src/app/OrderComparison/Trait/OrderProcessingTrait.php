<?php

namespace App\OrderComparison\Trait;

trait OrderProcessingTrait
{
    public function placeOrder(int $productId, int $quantity): array
    {
        if (! $this->stockService->hasStock($productId, $quantity)) {
            return ['success' => false, 'message' => 'Insufficient stock'];
        }

        $total = $this->priceService->calculate($productId, $quantity);

        return [
            'success' => true,
            'order' => $this->orderRepository->save($productId, $quantity, $total),
        ];
    }
}
