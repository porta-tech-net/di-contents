<?php

namespace App\OrderComparison\ManualDi;

class CouponService
{
    public function calculateDiscount(
        int $total,
        string $couponCode
    ): int {
        if ($couponCode === 'OFF500') {
            return min(500, $total);
        }

        if ($couponCode === 'OFF10') {
            return (int) floor($total * 0.10);
        }

        return 0;
    }
}
