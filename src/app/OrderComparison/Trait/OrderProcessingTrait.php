<?php

namespace App\OrderComparison\Trait;

trait OrderProcessingTrait
{
    public function placeOrder(
        int $productId,
        int $quantity,
        string $orderType = 'normal'
    ): array {
        if (
            $orderType !== 'reservation'
            && ! $this->stockService->hasStock($productId, $quantity)
        ) {
            return [
                'success' => false,
                'message' => 'Insufficient stock'
            ];
        }

        $amounts = $this->priceService->calculate(
            $productId,
            $quantity
        );

        return [
            'success' => true,
            'order' => $this->orderRepository->save(
                $productId,
                $quantity,
                $amounts['subtotal'],
                $amounts['tax'],
                $amounts['total']
            ),
        ];
    }
}
