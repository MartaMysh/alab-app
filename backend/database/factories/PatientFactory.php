<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition()
    {
        $name = $this->faker->firstName;
        $surname = $this->faker->lastName;
        $birthday = $this->faker->date();
        return [
            'patient_id' => $this->faker->unique()->randomNumber(5, true),
            'name' => $name,
            'surname' => $surname,
            'sex' => $this->faker->randomElement(['m','f']),
            'birth_date' => $birthday,
            'login' => $name.$surname,
            'password' => Hash::make($birthday),
        ];
    }
}