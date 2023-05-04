<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 403,
                'message' => 'Invalid credentials',
            ], 403);
        }

        return response()->json([
            'data' => LoginResource::make($user),
            'token' => $user->createToken('User-Token')->plainTextToken,
            'status' => 200
        ]);
    }
}
