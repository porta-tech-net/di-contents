<?php

namespace App\OrderComparison\Trait;

class MemberService
{
    public function getDiscountRate(int $memberId): float
    {
        return 0.15;
    }
}