<?php

namespace App\OrderComparison\ManualDi;

class MemberService
{
    public function getDiscountRate(int $memberId): float
    {
        return 0.15;
    }
}