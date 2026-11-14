<?php

namespace App\OrderComparison\Lifetime;

class LifetimeService
{
    public int $count = 0;

    public function increment(): void
    {
        $this->count++;
    }
}