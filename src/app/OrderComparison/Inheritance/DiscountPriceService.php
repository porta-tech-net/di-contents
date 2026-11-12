<?php

namespace App\OrderComparison\Inheritance;

class DiscountPriceService
{
    private array $prices = [1001 => 1000];

    public function calculate(int $productId, int $quantity): array
    {
        if (! isset($this->prices[$productId])) {
            throw new \InvalidArgumentException('Unknown product');
        }

        $subtotal = $this->prices[$productId] * $quantity;
        $discount = (int) floor($subtotal * 0.10);
        $subtotal -= $discount;

        $tax = (int) floor($subtotal * 0.08);

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $subtotal + $tax,
        ];
    }
}
