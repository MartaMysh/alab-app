<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'login' => [
                'required',
                'string',
                'regex:/^[A-Z][a-z]+[A-Z][a-z]+$/'
            ],
            'password' => [
                'required',
                'date_format:Y-m-d'
            ],
        ]);

        $patient = Patient::where('login', $request->login)
            ->where('birth_date', $request->password)
            ->first();

        if (!$patient || !Hash::check($request->password, $patient->password)) {
            return response()->json([
                'error' => 'Invalid credentials'
            ], 401);
        }

        $token = JWTAuth::fromUser($patient);

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer'
        ]);
    }
}