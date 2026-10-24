<?php

namespace Tests\Feature;

use App\Models\Kot;
use App\Models\KotItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderReceiptTest extends TestCase
{
    use RefreshDatabase;

    public function test_printing_invoice_completes_order_and_serves_its_kots(): void
    {
        $user = User::factory()->create([
            'all_branches' => true,
        ]);

        $order = Order::create([
            'order_number' => 'ORD-RECEIPT-1',
            'order_type' => 'takeaway',
            'status' => 'preparing',
        ]);

        $kot = Kot::create([
            'kot_number' => 'KOT-RECEIPT-1',
            'order_id' => $order->id,
            'status' => 'ready',
        ]);

        $kotItem = KotItem::create([
            'kot_id' => $kot->id,
            'item_name' => 'Burger',
            'quantity' => 1,
            'status' => 'ready',
        ]);

        $response = $this->actingAs($user)
            ->post(route('admin.orders.receipt', $order));

        $response->assertOk()
            ->assertViewIs('admin.orders.receipt');
        $this->assertSame('completed', $order->fresh()->status);
        $this->assertSame('served', $kot->fresh()->status);
        $this->assertNotNull($kot->fresh()->served_at);
        $this->assertSame('served', $kotItem->fresh()->status);
    }
}
