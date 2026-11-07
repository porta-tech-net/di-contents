<?php

namespace App\OrderComparison\ManualDi;

class PriceService
{
    private array $prices = [1001 => 1000];

    public function calculate(int $productId, int $quantity): array
    {
        if (! isset($this->prices[$productId])) {
            throw new \InvalidArgumentException('Unknown product');
        }

        $subtotal = $this->prices[$productId] * $quantity;
        $tax = (int) floor($subtotal * 0.10);

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $subtotal + $tax,
        ];
    }
}
