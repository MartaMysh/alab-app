<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Patient;
use App\Models\Order;
use App\Models\TestResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ResultTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_can_get_results_with_valid_token()
    {
        config(['auth.defaults.guard' => 'api']);

        $patient = Patient::factory()
            ->has(
                Order::factory()
                    ->has(TestResult::factory()->count(2), 'results')
                    ->count(2)
            )
            ->create();

        $token = JWTAuth::fromUser($patient);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/results');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'patient' => ['id','name','surname','sex','birthDate'],
                'orders' => [
                    ['orderId','results' => [['name','value','reference']]]
                ]
            ]);
    }

    public function test_get_results_fails_without_token()
    {
        $response = $this->getJson('/api/results');
        $response->assertStatus(401);
    }
}