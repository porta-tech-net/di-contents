<?php

namespace App\OrderComparison\ManualDi;

class ConstructorCouponOrderService
{
    public function __construct(
        private StockService $stockService,
        private DiscountPriceService $priceService,
        private OrderRepository $orderRepository,
        private CouponService $couponService
    ) {
    }

    public function placeOrder(
        int $productId,
        int $quantity
    ): array {
        return $this->saveOrder(
            $productId,
            $quantity
        );
    }

    public function placeOrderWithCoupon(
        int $productId,
        int $quantity,
        string $couponCode
    ): array {
        return $this->saveOrder(
            $productId,
            $quantity,
            $couponCode
        );
    }

    private function saveOrder(
        int $productId,
        int $quantity,
        ?string $couponCode = null
    ): array {
        if (! $this->stockService->hasStock(
            $productId,
            $quantity
        )) {
            return [
                'success' => false,
                'message' => 'Insufficient stock'
            ];
        }

        $amounts = $this->priceService->calculate(
            $productId,
            $quantity
        );

        if ($couponCode !== null) {
            $discount = $this->couponService->calculateDiscount(
                $amounts['total'],
                $couponCode
            );

            $amounts['total'] -= $discount;
        }

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
