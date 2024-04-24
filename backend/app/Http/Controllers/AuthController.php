<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     tags={"Auth Endpoint"},
     *     summary="Create user",
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/RegisterRequest")
     *         )
     *     ),
     *     @OA\Response(
     *          response=201,
     *          description="Created",
     *          @OA\JsonContent(
     *              oneOf={
     *                  @OA\Schema(
     *                      @OA\Property(property="message", type="string", example="User registered successfully!"),
     *                      @OA\Property(property="data", type="object",
     *                           @OA\Property(property="token", type="string", format="token"),
     *                           @OA\Property(property="type", type="string", example="Bearer"),
     *                      )
     *                 )
     *             }
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        $user = User::create($validated);
        $token = Auth::login($user);
        return response()->json([
            "message" => "User registered successfully!",
            "data" => ['token' => $token, 'type' => 'Bearer'],
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"Auth Endpoint"},
     *     summary="Login user",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/LoginRequest")
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              oneOf={
     *                  @OA\Schema(
     *                      @OA\Property(property="message", type="string", example="Login successfully!"),
     *                      @OA\Property(property="data", type="object",
     *                          @OA\Property(property="token", type="string", format="token"),
     *                          @OA\Property(property="type", type="string", example="Bearer"),
     *                      )
     *                  )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              oneOf={
     *                  @OA\Schema(
     *                      @OA\Property(property="message", type="string", example="email or password invalid")
     *                 )
     *             }
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        $token = Auth::attempt($validated);
        if (!$token)
            return response()->json(["message" => "email or password invalid"], 401);
        Auth::user();
        return response()->json([
            "message" => "Login successfully!",
            "data" => ['token' => $token, 'type' => 'Bearer'],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     tags={"Auth Endpoint"},
     *     summary="Logout user and delete tokens",
     *     security={{"bearer_token":{}}},
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              oneOf={
     *                  @OA\Schema(@OA\Property(property="message", type="string", example="Logout successfully!")),
     *             }
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json([
            "message" => "Logout successfully!",
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/refresh",
     *     tags={"Auth Endpoint"},
     *     summary="Refresh tokens",
     *     security={{"bearer_token":{}}},
     *     @OA\Response(
     *           response=200,
     *           description="OK",
     *           @OA\JsonContent(
     *               oneOf={
     *                   @OA\Schema(@OA\Property(property="message", type="string", example="Token refreshed successfully!")),
     *              }
     *          )
     *      )
     * )
     */
    public function refresh(Request $request)
    {
        return response()->json([
            "message" => "Token refreshed successfully!",
            "data" => ['token' => Auth::refresh(), 'type' => 'Bearer'],
        ]);
    }
}
