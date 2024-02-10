<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Request;

class AuthApiController extends Controller
{
    public function register(RegisterRequest $request): object
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            $token = $user->createToken('auth-token');
            $expiration = config("sanctum.expiration") * 60;

            $msg = [
                "token_type" => "Bearer",
                "expiration" => $expiration,
                "token_access" => $token->plainTextToken,
            ];

            $cookie = cookie("auth-token", $token->plainTextToken, $expiration);

            return response()->json(
                successResponse(200, "Register was successfully", (new UserResource($user)), $msg)
            )->withCookie($cookie);
        } catch (Exception $error) {
            return response()->json(
                errorResponse(500, "Internal server error", $error->getMessage()), 500
            );
        }
    }

    public function login(): object
    {
        try {
            $credentials = request(['email', 'password']);

            if (!auth()->attempt($credentials)) {
                return response()->json(
                    failResponse(401, "Unauthorized"), 401
                );
            }

            $user = auth()->user();

            $token = $user->createToken('auth-token');
            $expiration = config("sanctum.expiration") * 60;

            $msg = [
                "token_type" => "Bearer",
                "expiration" => $expiration,
                "token_access" => $token->plainTextToken,
            ];

            $cookie = cookie("auth-token", $token->plainTextToken, $expiration);

            return response()->json(
                successResponse(200, "Login was successfully", (new UserResource($user)), $msg)
            )->withCookie($cookie);
        } catch (Exception $error) {
            return response()->json(
                errorResponse(500, "Internal server error", $error->getMessage()), 500
            );
        }
    }

    public function logout(): object
    {
        try {
            $user = auth()->user();
            $tokenId = Request::bearerToken();
            dd($user->tokens);
            $token = $user->tokens->find($tokenId);

            if ($token) {
                $token->delete();
                return response()->json(['message' => 'Token revoked successfully']);
            }

            return response()->json(['error' => 'Token not found'], 404);
        } catch (Exception $error) {
            return response()->json(
                errorResponse(500, "Internal server error", $error->getMessage()), 500
            );
        }
    }

    public function refresh(): object
    {
        try {
            $user = auth()->user();

            $token = $user->createToken('auth-token');
            $expiration = config("sanctum.expiration") * 60;

            $msg = [
                "token_type" => "Bearer",
                "expiration" => $expiration,
                "token_access" => $token->plainTextToken,
            ];

            return response()->json(
                successResponse(200, "Refresh token successfully", $msg)
            );
        } catch (Exception $error) {
            return response()->json(
                errorResponse(500, "Internal server error", $error->getMessage()), 500
            );
        }
    }

    public function me(): object
    {
        try {
            return response()->json(
                successResponse(200, "Get current user successfully", auth()->user())
            );
        } catch (Exception $error) {
            return response()->json(
                errorResponse(500, "Internal server error", $error->getMessage()), 500
            );
        }
    }
}
