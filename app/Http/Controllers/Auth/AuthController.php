<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    public function getToken(
        Request $request,
        UserService $userService,
    ): JsonResponse {
        $data = $request->validate([
            'phone' => ['required', 'string'],
        ]);

        $user      = $userService->getUser($data['phone']);
        $user      = $user ?? $userService->createUser($data['phone']);
        $profileId = $user->profile()->first()?->user_id;

        $token = $user->createToken('AuthToken')->plainTextToken;

        return Response::json([
            'token' => $token,
            'profile_id' => $profileId
        ]);
    }

}
