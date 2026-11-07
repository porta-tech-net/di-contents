<?php

namespace App\OrderComparison\ContainerInterface;

class PriceService implements PriceServiceInterface
{
    private array $prices = [1001 => 1000];

    public function calculate(int $productId, int $quantity): int
    {
        if (! isset($this->prices[$productId])) {
            throw new \InvalidArgumentException('Unknown product');
        }

        return $this->prices[$productId] * $quantity;
    }
}
