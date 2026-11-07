<?php

namespace App\OrderComparison\ContainerInterface;

interface OrderRepositoryInterface
{
    public function save(int $productId, int $quantity, int $total): array;
}
