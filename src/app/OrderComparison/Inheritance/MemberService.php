<?php

namespace App\OrderComparison\Inheritance;

class MemberService
{
    public function getDiscountRate(int $memberId): float
    {
        return 0.20;
    }
}