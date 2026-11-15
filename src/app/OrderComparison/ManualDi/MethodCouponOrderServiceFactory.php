<?php

namespace App\OrderComparison\ManualDi;

class MethodCouponOrderServiceFactory
{
    public static function create(): CouponOrderService
    {
        return new CouponOrderService(
            new StockService(),
            new DiscountPriceService(
                new MemberService()
            ),
            new OrderRepository()
        );
    }
    
    public static function createCouponService(): CouponService
    {
        // キャンペーン状態を共通Policyから取得
        $campaign = CouponCampaignPolicy::isCampaignActive();

        return $campaign
            ? new CampaignCouponService()
            : new CouponService();
    }
}
