<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Models\Admin;
use App\Traits\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ResponseHelper;

    public function login(LoginRequest $request): JsonResponse
    {
        $admin = Admin::whereEmail($request->email)->first();

        if (! $admin) {
            return $this->responseFailed(__('auth.failed'), 401);
        }

        if (! Hash::check($request->password, $admin->password)) {
            return $this->responseFailed(__('auth.failed'), 401);
        }

        return $this->responseSucceed(data: [
            'token' => $admin->createToken('ADMIN TOKEN')->plainTextToken,
            'admin' => $admin,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->responseSucceed([], 'logout successfully');
    }
}
