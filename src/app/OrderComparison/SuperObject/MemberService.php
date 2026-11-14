<?php

namespace App\OrderComparison\SuperObject;

class MemberService
{
    public function getDiscountRate(int $memberId): float
    {
        return 0.20;
    }
}