<?php

namespace Tests\Feature;

use App\Services\ShippingCalculator;
use Tests\TestCase;

class ShippingCalculatorTest extends TestCase
{
    public function test_it_calculates_shipping_fee_based_on_weight_and_courier(): void
    {
        $calculator = new ShippingCalculator();

        $this->assertSame(16100, $calculator->calculate('jne', 'REG', '531', 2500));
        $this->assertSame(9350, $calculator->calculate('tiki', 'REG', '113', 2000));
        $this->assertSame(12000, $calculator->calculate('pos', 'REG', '153', 1000));
    }
}
