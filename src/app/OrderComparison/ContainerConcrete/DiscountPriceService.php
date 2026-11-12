<?php

namespace App\OrderComparison\ContainerConcrete;

class DiscountPriceService extends PriceService
{

    public function __construct(
        private MemberService $memberService
    ) {
    }

    public function calculate(int $productId, int $quantity): array
    {
        $amounts = parent::calculate($productId, $quantity);
        $discountRate = $this->memberService->getDiscountRate(1);

        $discount = (int) floor($amounts['subtotal'] * $discountRate);
        $subtotal = $amounts['subtotal'] - $discount;
        $tax = (int) floor($subtotal * 0.08);

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $subtotal + $tax,
        ];
    }
}
