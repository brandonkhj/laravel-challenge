<?php

namespace App\Services\InternetServiceProvider;

interface InternetInterface
{
    public function setMonth(int $month);

    public function calculateTotalAmount();
}
