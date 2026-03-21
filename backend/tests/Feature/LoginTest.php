<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_can_login_with_valid_credentials()
    {
        $patient = Patient::factory()->create([
            'name' => 'Piotr',
            'surname' => 'Kowalski',
            'birth_date' => '1983-04-12',
            'login' => 'PiotrKowalski',
            'password' => bcrypt('1983-04-12')
        ]);

        $response = $this->postJson('/api/login', [
            'login' => 'PiotrKowalski',
            'password' => '1983-04-12',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function test_login_fails_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'login' => 'NieistniejeNieistnieje',
            'password' => '2001-03-05',
        ]);

        $response->assertStatus(401);
    }
}