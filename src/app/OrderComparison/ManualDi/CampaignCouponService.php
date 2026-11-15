<?php

namespace App\OrderComparison\ManualDi;

class CampaignCouponService extends CouponService
{
    public function calculateDiscount(
        int $total,
        string $couponCode
    ): int {
        if ($couponCode === 'OFF500') {
            return min(1000, $total);
        }

        if ($couponCode === 'OFF10') {
            return (int) floor($total * 0.20);
        }

        return 0;
    }
}
