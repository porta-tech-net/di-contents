<?php

namespace App\OrderComparison\ManualDi;

class DiscountPriceService
{
    private array $prices = [1001 => 1000];

    public function calculate(int $productId, int $quantity): array
    {
        if (! isset($this->prices[$productId])) {
            throw new \InvalidArgumentException('Unknown product');
        }

        $subtotal = $this->prices[$productId] * $quantity;

        $discountedSubtotal = $subtotal
            - (int) floor($subtotal * 0.10);

        $tax = (int) floor($discountedSubtotal * 0.10);

        return [
            'subtotal' => $discountedSubtotal,
            'tax' => $tax,
            'total' => $discountedSubtotal + $tax,
        ];
    }
}
