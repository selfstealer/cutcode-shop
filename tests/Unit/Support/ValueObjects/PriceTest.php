<?php

declare(strict_types=1);

namespace Support\ValueObjects;

use Tests\TestCase;

class PriceTest extends TestCase
{
    /**
     * @test
     */
    public function it_all(): void
    {
        $price = Price::make(10000);

        $this->assertInstanceOf(Price::class, $price);
        $this->assertEquals(100, $price->value());
        $this->assertEquals(10000, $price->rawValue());
        $this->assertEquals('RUB', $price->currency());
        $this->assertEquals('₽', $price->symbol());
        $this->assertEquals('100,00 ₽', $price);

        $this->expectException(\InvalidArgumentException::class);
        $price = Price::make(-10000);
        $price = Price::make(10000, 'USD');
    }
}
