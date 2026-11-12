<?php

namespace App\OrderComparison\ManualDi;

class DiscountPriceService
{
    private array $prices = [1001 => 1000];

    public function __construct(
        private MemberService $memberService
    ) {
    }

    public function calculate(int $productId, int $quantity): array
    {
        if (! isset($this->prices[$productId])) {
            throw new \InvalidArgumentException('Unknown product');
        }

        $subtotal = $this->prices[$productId] * $quantity;
        $discountRate = $this->memberService->getDiscountRate(1);

        $discountedSubtotal = $subtotal
            - (int) floor($subtotal * $discountRate);

        $tax = (int) floor($discountedSubtotal * 0.08);

        return [
            'subtotal' => $discountedSubtotal,
            'tax' => $tax,
            'total' => $discountedSubtotal + $tax,
        ];
    }
}
