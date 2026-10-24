<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderPaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_method_can_be_recorded_from_order_details(): void
    {
        $user = User::factory()->create([
            'all_branches' => true,
        ]);

        $order = Order::create([
            'order_number' => 'ORD-PAYMENT-1',
            'order_type' => 'takeaway',
            'status' => 'preparing',
        ]);

        $response = $this->actingAs($user)->put(
            route('admin.orders.payment-method', $order),
            ['payment_method' => 'upi']
        );

        $response->assertRedirect();
        $this->assertSame('upi', $order->fresh()->payment_method);
    }
}
