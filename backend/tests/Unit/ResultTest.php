<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Models\TestResult;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResultTest extends TestCase
{
    use RefreshDatabase;

    public function test_result_attributes_and_relationship()
    {
        $order = Order::factory()->create();
        $result = new TestResult([
            'order_id' => $order->id,
            'name' => 'Glucose',
            'value' => '5.5',
            'reference' => '4-6',
        ]);

        $this->assertEquals($order->id, $result->order_id);
        $this->assertEquals('Glucose', $result->name);
        $this->assertEquals('5.5', $result->value);
        $this->assertEquals('4-6', $result->reference);
    }
}