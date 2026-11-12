<?php

namespace App\OrderComparison\SuperObject;

/** Comparison-only shared service holder, modeled after CI2's superobject access. */
class SuperObject
{
    public readonly StockService $stockService;
    public readonly DiscountPriceService $priceService;
    public readonly OrderRepository $orderRepository;
    public readonly MemberService $memberService;

    public function __construct()
    {
        $this->stockService = new StockService();
        $this->orderRepository = new OrderRepository();
        $this->memberService = new MemberService();
        $this->priceService = new DiscountPriceService(
            $this->memberService
        );
    }
}
