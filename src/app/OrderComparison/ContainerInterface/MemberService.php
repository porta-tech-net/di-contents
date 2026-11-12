<?php

namespace App\OrderComparison\ContainerInterface;

class MemberService
{
    public function getDiscountRate(int $memberId): float
    {
        return 0.15;
    }
}