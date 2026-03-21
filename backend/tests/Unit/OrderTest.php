<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_attributes_and_relationship()
    {
        $patient = Patient::factory()->create();

        $order = Order::factory()->create([
            'patient_id' => $patient->id,
            'order_identifier' => 'ORD1001',
        ]);

        $this->assertEquals($patient->id, $order->patient_id);
        $this->assertEquals('ORD1001', $order->order_identifier);
        $this->assertEquals($patient->id, $order->patient->id);
    }
}