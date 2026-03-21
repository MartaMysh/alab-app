<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'order_identifier',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function results()
    {
        return $this->hasMany(TestResult::class);
    }
}
