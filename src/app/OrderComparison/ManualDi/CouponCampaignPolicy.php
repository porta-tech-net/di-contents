<?php

namespace App\OrderComparison\ManualDi;

class CouponCampaignPolicy
{
    public static function isCampaignActive(): bool
    {
        return false;
    }
}
