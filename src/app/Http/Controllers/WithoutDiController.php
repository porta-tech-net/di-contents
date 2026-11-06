<?php

namespace App\Http\Controllers;

use App\Services\MessageService;

class WithoutDiController extends Controller
{
    public function index(): string
    {
        $service = new MessageService();

        return $service->getMessage();
    }
}