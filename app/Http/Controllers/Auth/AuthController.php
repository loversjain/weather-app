<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoggedInRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class AuthController
 *
 * Controller class for handling authentication operations.
 *
 * @package App\Http\Controllers\Auth
 */
class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param RegisterRequest $request The incoming registration request.
     * @return \Illuminate\Http\JsonResponse Returns JSON response indicating registration status.
     */
    public function register(RegisterRequest $request)
    {
        try {
            $userRow = User::createUser($request->all());
            if (!empty($userRow)) {
                Log::info("Registration successful!", ['response' => $userRow]);
                return response()->json([
                    'status' => true,
                    'message' => 'Registration successful!',
                    'user' => $userRow,
                ], 201);
            } else {
                Log::error("Registration Unsuccessful! No user row created");
                return response()->json(['message' => 'Registration Unsuccessful!'], 400);
            }
        } catch (\Exception $exception) {
            Log::error("Unexpected error during registration",
                ['error' => $exception->getMessage(), 'trace' => $exception->getTraceAsString()]);
            return response()->json(['message' => 'Unexpected error during registration'], 500);
        }
    }

    /**
     * Login a user.
     *
     * @param LoggedInRequest $request The incoming login request.
     * @return \Illuminate\Http\JsonResponse Returns JSON response indicating login status and user information.
     */
    public function login(LoggedInRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = JWTAuth::fromUser(Auth::user());
            return response()->json(['message' => 'Logged in Successfully', 'token' => $token, 'user' => $user], 200);
        }

        return response()->json(['error' => 'Unauthorized', 'credentials' => $credentials], 401);
    }

    /**
     * Logout a user.
     *
     * @param Request $request The incoming logout request.
     * @return \Illuminate\Http\JsonResponse Returns JSON response indicating successful logout.
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
