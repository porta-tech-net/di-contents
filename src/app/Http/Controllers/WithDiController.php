<?php

namespace App\Http\Controllers;

use App\Contracts\MessageServiceInterface;

class WithDiController extends Controller
{
    public function __construct(
        private MessageServiceInterface $service
    ) {
    }

    public function index(): string
    {
        return $this->service->getMessage();
    }
}