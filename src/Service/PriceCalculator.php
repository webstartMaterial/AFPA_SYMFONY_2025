<?php

namespace App\Service;

class PriceCalculator
{
    public function applyDiscount(float $price, float $discount): float
    {
        if ($discount < 0 || $discount > 100) {
            throw new \InvalidArgumentException("Discount must be between 0 and 100.");
        }

        return $price * (1 - $discount / 100);
    }
}
