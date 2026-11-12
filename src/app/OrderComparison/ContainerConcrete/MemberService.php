<?php

namespace App\OrderComparison\ContainerConcrete;

class MemberService
{
    public function getDiscountRate(int $memberId): float
    {
        return 0.15;
    }
}