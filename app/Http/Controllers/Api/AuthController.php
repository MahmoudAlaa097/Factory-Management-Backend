<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\AuthResource;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $request->validated($request->all());

        if (!Auth::attempt($request->only('username', 'password'))) {
            return ApiResponse::unauthorized();
        }

        $user = User::with('employee.management', 'employee.division')
                    ->firstWhere('username', $request->username);

        $token = $user->createToken(
            'API token for ' . $user->username,
            ['*'],
            now()->addMonth()
        )->plainTextToken;

        return ApiResponse::success('Authenticated', new AuthResource($user, $token));
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::success('Logged out');
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('employee.management', 'employee.division');

        return ApiResponse::success('Authenticated', new AuthResource($user, ''));
    }
}
