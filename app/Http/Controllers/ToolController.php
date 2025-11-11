<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function weather(Request $request)
    {
        $city = $request->input('city', 'Paris');
        return response()->json([
            'city' => $city,
            'temperature_c' => 19.2,
            'condition' => 'Cloudy',
            'observed_at' => now()->toIso8601String(),
        ]);
    }

    public function echo(Request $request)
    {
        return response()->json([
            'received' => $request->all(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
