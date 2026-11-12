<?php

namespace App\OrderComparison\DirectNew;

class DiscountPriceService
{
    private array $prices = [1001 => 1000];

    public function calculate(int $productId, int $quantity): array
    {
        if (! isset($this->prices[$productId])) {
            throw new \InvalidArgumentException('Unknown product');
        }

        $memberService = new MemberService();
        $discountRate = $memberService->getDiscountRate(1);

        $subtotal = $this->prices[$productId] * $quantity;

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
