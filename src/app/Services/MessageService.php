<?php

namespace App\Services;
use App\Contracts\MessageServiceInterface;

class MessageService implements MessageServiceInterface
{
    private int $count = 0;

    public function getMessage(): string
    {
        $this->count++;

        return "Count: {$this->count}";
    }
}