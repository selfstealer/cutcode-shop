<?php

declare(strict_types=1);

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\CartController;
use Database\Factories\ProductFactory;
use Domain\Cart\CartManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        CartManager::fake();
    }

    /**
     * @test
     */
    public function it_is_empty_cart(): void
    {
        $this->get(action([CartController::class, 'index']))
            ->assertOk()
            ->assertViewIs('cart.index')
            ->assertViewHas('items', collect());
    }

    /**
     * @test
     */
    public function it_is_not_empty_cart(): void
    {
        $product = ProductFactory::new()->createOne();

        cart()->add($product);

        $this->get(action([CartController::class, 'index']))
            ->assertOk()
            ->assertViewIs('cart.index')
            ->assertViewHas('items', cart()->items());
    }

    /**
     * @test
     */
    public function it_added_success(): void
    {
        $product = ProductFactory::new()->createOne();

        $this->assertEquals(0, cart()->count());

        $this->post(
            action([CartController::class, 'add'], $product),
            ['quantity' => 4]
        );

        $this->assertEquals(4, cart()->count());
    }

    /**
     * @test
     */
    public function it_quantity_changed(): void
    {
        $product = ProductFactory::new()->createOne();

        cart()->add($product, 4);

        $this->assertEquals(4, cart()->count());

        $this->post(
            action([CartController::class, 'quantity'], cart()->items()->first()),
            ['quantity' => 8]
        );

        $this->assertEquals(8, cart()->count());
    }

    /**
     * @test
     */
    public function it_delete_success(): void
    {
        $product = ProductFactory::new()->createOne();
        $product2 = ProductFactory::new()->createOne();

        cart()->add($product, 4);
        cart()->add($product2, 2);

        $this->assertEquals(6, cart()->count());

        $this->delete(action([CartController::class, 'delete'], cart()->items()->first()));

        $this->assertEquals(2, cart()->count());
    }

    /**
     * @test
     */
    public function it_truncate_success(): void
    {
        $product = ProductFactory::new()->createOne();
        $product2 = ProductFactory::new()->createOne();

        cart()->add($product, 4);
        cart()->add($product2, 2);

        $this->assertEquals(6, cart()->count());

        $this->delete(action([CartController::class, 'truncate']));

        $this->assertEquals(0, cart()->count());
    }
}
