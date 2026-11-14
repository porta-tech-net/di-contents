<?php

namespace App\OrderComparison\DirectNew;

class OrderService
{
    public function placeOrder(
        int $productId,
        int $quantity,
        string $orderType = 'normal'
    ): array {
        $stock = new StockService();
        $price = new DiscountPriceService();
        $repository = new OrderRepository();

        if ($orderType === 'reservation' && $quantity > 5) {
            return [
                'success' => false,
                'message' => 'Reservation quantity limit exceeded'
            ];
        }

        if ($orderType !== 'reservation'
            && ! $stock->hasStock($productId, $quantity)) {
            return [
                'success' => false,
                'message' => 'Insufficient stock'
            ];
        }

        $amounts = $price->calculate($productId, $quantity);

        return [
            'success' => true,
            'order' => $repository->save(
                $productId,
                $quantity,
                $amounts['subtotal'],
                $amounts['tax'],
                $amounts['total']
            ),
        ];
    }
}