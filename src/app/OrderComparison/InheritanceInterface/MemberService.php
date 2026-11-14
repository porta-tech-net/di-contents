<?php

namespace App\OrderComparison\InheritanceInterface;

class MemberService
{
    public function getDiscountRate(int $memberId): float
    {
        return 0.20;
    }
}