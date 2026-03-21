<?php

namespace Database\Factories;

use App\Models\TestResult;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestResultFactory extends Factory
{
    protected $model = TestResult::class;

    public function definition()
    {
        return [
            'order_id' => Order::factory(),
            'name' => $this->faker->word,
            'value' => $this->faker->randomFloat(2, 0, 100),
            'reference' => '0-100',
        ];
    }
}