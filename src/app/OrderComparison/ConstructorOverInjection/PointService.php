<?php

namespace App\OrderComparison\ConstructorOverInjection;

class PointService
{
    public function grant(int $amount): array
    {
        $points = intdiv($amount, 100);

        return [
            'success' => true,
            'amount' => $amount,
            'points' => $points,
            'status' => 'granted',
        ];
    }
}