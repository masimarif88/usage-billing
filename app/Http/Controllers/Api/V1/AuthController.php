<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Plan;
use App\Models\Subscription;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        


        $user = Auth::user(); // ✅ user is now defined

        $token = $user->createToken('postman')->plainTextToken;

        // Assign default plan if no active subscription
        if (!$user->activeSubscription()) {
                $plan = Plan::where('name', 'Free')->first();

                Subscription::create([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'starts_at' => now(),
                    'status' => 'active',
                ]);
        }

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }
}
