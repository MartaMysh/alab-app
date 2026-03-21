<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Patient;

class PatientTest extends TestCase
{
    public function test_patient_attributes()
    {
        $patient = new Patient([
            'patient_id' => 12345,
            'name' => 'Jan',
            'surname' => 'Kowalski',
            'sex' => 'm',
            'birth_date' => '1983-04-12',
            'login' => 'JanKowalski',
            'password' => bcrypt('1983-04-12'),
        ]);

        $this->assertEquals('Jan', $patient->name);
        $this->assertEquals('Kowalski', $patient->surname);
        $this->assertEquals('m', $patient->sex);
        $this->assertEquals('1983-04-12', $patient->birth_date);
        $this->assertEquals('JanKowalski', $patient->login);
    }
}