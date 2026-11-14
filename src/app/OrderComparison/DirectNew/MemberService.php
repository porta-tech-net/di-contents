<?php

namespace App\OrderComparison\DirectNew;

class MemberService
{
    public function getDiscountRate(int $memberId): float
    {
        return 0.20;
    }
}