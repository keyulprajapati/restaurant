<?php

namespace Tests\Feature;

use App\Models\Combo;
use App\Models\ComboItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosComboOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_combo_items_can_be_added_to_a_pos_order(): void
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

        $combo = Combo::create([
            'name' => 'Family Combo',
            'price' => 350.00,
            'is_active' => true,
        ]);

        ComboItem::create([
            'combo_id' => $combo->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->post(route('admin.pos.store'), [
            'order_type' => 'takeaway',
            'customer_id' => 'walk_in',
            'items' => [
                [
                    'type' => 'combo',
                    'id' => $combo->id,
                    'quantity' => 1,
                ],
            ],
            'discount' => 0,
            'notes' => 'Combo order',
        ]);

        $response->assertRedirect(route('admin.pos.index', absolute: false));

        $order = Order::query()->first();
        $this->assertNotNull($order);
        $this->assertSame('Family Combo (Combo)', $order->items()->first()->item_name);
        $this->assertSame(350.00, (float) $order->items()->first()->total);
    }
}
