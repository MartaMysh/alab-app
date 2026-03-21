<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;

class ResultsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $patient = JWTAuth::parseToken()->authenticate();

        if (!$patient) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        $orders = $patient->orders()->with('results')->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'error' => 'No results found'
            ], 404);
        }


        return response()->json([
            'patient' => [
                'id' => $patient->id,
                'name' => $patient->name,
                'surname' => $patient->surname,
                'sex' => $patient->sex,
                'birthDate' => $patient->birth_date,
            ],
            'orders' => $orders->map(function ($order) {
                return [
                    'orderId' => $order->order_identifier,
                    'results' => $order->results->map(function ($result) {
                        return [
                            'name' => $result->name,
                            'value' => $result->value,
                            'reference' => $result->reference,
                        ];
                    })
                ];
            })
        ]);
    }
}