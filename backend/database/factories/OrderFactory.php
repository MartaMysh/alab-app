<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'patient_id' => Patient::factory(),
            'order_identifier' => $this->faker->unique()->numerify('ORD###'),
        ];
    }
}