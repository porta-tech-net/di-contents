<?php

namespace App\OrderComparison\ContainerConcrete;

class DiscountPriceService extends PriceService
{
    public function calculate(int $productId, int $quantity): array
    {
        $amounts = parent::calculate($productId, $quantity);

        $discount = (int) floor($amounts['subtotal'] * 0.10);
        $subtotal = $amounts['subtotal'] - $discount;
        $tax = (int) floor($subtotal * 0.10);

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $subtotal + $tax,
        ];
    }
}
