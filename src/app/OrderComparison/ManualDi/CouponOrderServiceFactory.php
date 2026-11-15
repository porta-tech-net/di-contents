<?php

namespace App\OrderComparison\ManualDi;

class CouponOrderServiceFactory
{
    public static function create(): ConstructorCouponOrderService
    {
        // キャンペーン適用状態を共通の設定から取得
        $campaign = CouponCampaignPolicy::isCampaignActive();

        $couponService = $campaign
            ? new CampaignCouponService()
            : new CouponService();

        return new ConstructorCouponOrderService(
            new StockService(),
            new DiscountPriceService(
                new MemberService()
            ),
            new OrderRepository(),
            $couponService
        );
    }
}
