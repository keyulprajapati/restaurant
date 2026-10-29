<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Kot;
use App\Models\KotItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\RestaurantTable;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosOpenOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_pos_preloads_the_open_order_for_a_selected_table(): void
    {
        $user = User::factory()->create([
            'all_branches' => true,
        ]);
        [$branch, $table, $order, $product] = $this->createOpenTableOrder();

        $response = $this->actingAs($user)->get(route('admin.pos.index', [
            'branch_id' => $branch->id,
            'order_type' => 'dine_in',
            'restaurant_table_id' => $table->id,
        ]));

        $response->assertOk()
            ->assertSee($order->order_number)
            ->assertSee('Update Order')
            ->assertSee($product->name);
    }

    public function test_pos_shows_products_for_selected_branch_and_legacy_products(): void
    {
        $user = User::factory()->create([
            'all_branches' => true,
        ]);
        $branch = Branch::create([
            'name' => 'North Branch',
            'code' => 'POS-NORTH',
        ]);
        $otherBranch = Branch::create([
            'name' => 'South Branch',
            'code' => 'POS-SOUTH',
        ]);

        Product::create([
            'branch_id' => $branch->id,
            'name' => 'North Product',
            'price' => 100,
            'unit' => 'pcs',
            'food_type' => 'general',
            'is_active' => true,
        ]);
        Product::create([
            'branch_id' => $otherBranch->id,
            'name' => 'South Product',
            'price' => 100,
            'unit' => 'pcs',
            'food_type' => 'general',
            'is_active' => true,
        ]);
        Product::create([
            'name' => 'Legacy Product',
            'price' => 100,
            'unit' => 'pcs',
            'food_type' => 'general',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get(route('admin.pos.index', [
            'branch_id' => $branch->id,
        ]));

        $response->assertOk()
            ->assertSee('North Product')
            ->assertSee('Legacy Product')
            ->assertDontSee('South Product');
    }

    public function test_submitting_pos_updates_the_open_order_and_sends_only_added_quantity_to_kitchen(): void
    {
        $user = User::factory()->create([
            'all_branches' => true,
        ]);
        [$branch, $table, $order, $product, $orderItem] = $this->createOpenTableOrder();

        $response = $this->actingAs($user)->post(route('admin.pos.store'), [
            'existing_order_id' => $order->id,
            'branch_id' => $branch->id,
            'order_type' => 'dine_in',
            'restaurant_table_id' => $table->id,
            'customer_id' => 'walk_in',
            'items' => [
                [
                    'id' => $product->id,
                    'menu_item_id' => $product->id,
                    'type' => 'product',
                    'order_item_id' => $orderItem->id,
                    'quantity' => 2,
                ],
            ],
            'discount' => 0,
            'notes' => 'Updated at POS',
        ]);

        $response->assertRedirect();
        $this->assertSame(1, Order::whereKey($order->id)->count());
        $this->assertSame('200.00', $order->fresh()->grand_total);
        $this->assertSame('Updated at POS', $order->fresh()->notes);
        $this->assertSame('preparing', $order->fresh()->status);
        $this->assertSame('2.00', $orderItem->fresh()->quantity);
        $this->assertSame(2, Kot::where('order_id', $order->id)->count());
        $latestKot = Kot::where('order_id', $order->id)->latest()->first();
        $this->assertSame('1.00', $latestKot->items()->first()->quantity);
    }

    private function createOpenTableOrder(): array
    {
        $branch = Branch::create([
            'name' => 'POS Branch',
            'code' => 'POS-OPEN',
        ]);
        $table = RestaurantTable::create([
            'branch_id' => $branch->id,
            'table_number' => 'T1',
            'capacity' => 4,
            'status' => 'occupied',
            'is_active' => true,
        ]);
        $product = Product::create([
            'name' => 'Pizza',
            'price' => 100,
            'size' => null,
            'unit' => 'pcs',
            'food_type' => 'main',
            'is_active' => true,
        ]);
        $order = Order::create([
            'branch_id' => $branch->id,
            'order_number' => 'ORD-POS-OPEN',
            'restaurant_table_id' => $table->id,
            'order_type' => 'dine_in',
            'status' => 'preparing',
            'subtotal' => 100,
            'discount' => 0,
            'tax' => 0,
            'grand_total' => 100,
        ]);
        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'menu_item_id' => $product->id,
            'item_name' => $product->name,
            'unit_price' => 100,
            'quantity' => 1,
            'discount' => 0,
            'tax' => 0,
            'total' => 100,
        ]);
        $kot = Kot::create([
            'kot_number' => 'KOT-POS-OPEN',
            'order_id' => $order->id,
            'restaurant_table_id' => $table->id,
            'status' => 'preparing',
        ]);
        KotItem::create([
            'kot_id' => $kot->id,
            'order_item_id' => $orderItem->id,
            'item_name' => $product->name,
            'quantity' => 1,
            'status' => 'preparing',
        ]);

        return [$branch, $table, $order, $product, $orderItem];
    }
}
