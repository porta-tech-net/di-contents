<?php

namespace App\OrderComparison\SuperObject;

class OrderService
{
    public function __construct(private SuperObject $app)
    {
    }

    public function placeOrder(
        int $productId,
        int $quantity,
        string $orderType = 'normal'
    ): array {
        
        if ($orderType === 'reservation' && $quantity > 5) {
            return [
                'success' => false,
                'message' => 'Reservation quantity limit exceeded'
            ];
        }

        if (
            $orderType !== 'reservation'
            && ! $this->app->stockService->hasStock($productId, $quantity)
        ) {
            return [
                'success' => false,
                'message' => 'Insufficient stock'
            ];
        }

        $amounts = $this->app->priceService->calculate(
            $productId,
            $quantity
        );

        return [
            'success' => true,
            'order' => $this->app->orderRepository->save(
                $productId,
                $quantity,
                $amounts['subtotal'],
                $amounts['tax'],
                $amounts['total']
            ),
        ];
    }
}
