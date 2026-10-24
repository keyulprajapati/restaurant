<?php

namespace Tests\Feature;

use App\Models\Kot;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosAutoKotTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_creation_also_creates_kot_for_kitchen(): void
    {
        $user = User::factory()->create();

        $product = Product::create([
            'name' => 'Burger',
            'price' => 180.00,
            'size' => null,
            'unit' => 'pcs',
            'food_type' => 'main',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post(route('admin.pos.store'), [
            'order_type' => 'takeaway',
            'items' => [
                [
                    'menu_item_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
            'discount' => 0,
            'notes' => 'Extra napkins',
        ]);

        $response->assertRedirect(route('admin.pos.index', absolute: false));

        $order = Order::query()->first();
        $this->assertNotNull($order);
        $this->assertDatabaseHas('kots', [
            'order_id' => $order->id,
            'status' => 'pending',
            'notes' => 'Extra napkins',
        ]);

        $this->assertSame('preparing', $order->fresh()->status);
        $this->assertCount(1, $order->kots);
        $this->assertSame(2, (int) $order->kots()->first()->items()->first()->quantity);
    }
}
