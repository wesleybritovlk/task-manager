<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/user",
     *      tags={"User Endpoint"},
     *      summary="Get user info",
     *      description="Returns user data",
     *      security={{"bearer_token"={}}},
     *       @OA\Response(
     *            response=200,
     *            description="OK",
     *            @OA\JsonContent(ref="#/components/schemas/UserResource")
     *       )
     *  )
     */
    public function get(Request $request)
    {
        return new UserResource($request->user());
    }

    /**
     * @OA\Put(
     *      path="/api/user/{id}",
     *      tags={"User Endpoint"},
     *      summary="Put User by id",
     *      description="Returns id of the updated user",
     *      security={{"bearer_token"={}}},
     *      @OA\Parameter(
     *            in="path", name="id",
     *            description="Path to find user by id",
     *            required=true,
     *            @OA\Schema(type="string", format="uuid", example="2a825edc-95f6-49a9-b46a-d2f296e7e1ce")
     *       ),
     *       @OA\RequestBody(
     *            @OA\MediaType(
     *                mediaType="application/json",
     *                @OA\Schema(ref="#/components/schemas/UserRequest")
     *            )
     *       ),
     *       @OA\Response(
     *            response=200, description="Ok",
     *            @OA\JsonContent(
     *                oneOf={
     *                    @OA\Schema(
     *                        @OA\Property(property="message", type="string", example="User updated successfully!"),
     *                        @OA\Property(property="data", type="object",
     *                            @OA\Property(property="id", type="string", format="uuid")
     *                        )
     *                    )
     *                }
     *            )
     *       )
     *  )
     */
    public function update(UserRequest $request, User $user)
    {
        $id = $user->id;
        $validated = $request->validated();
        User::query()->where('id', $id)->update($validated);
        return response()->json([
            'message' => 'User updated successfully!',
            'data' => ['id' => $id]
        ]);
    }

    /**
     * @OA\Delete(
     *       path="/api/user/{id}",
     *       tags={"User Endpoint"},
     *       summary="Delete user by id",
     *       security={{"bearer_token"={}}},
     *       @OA\Parameter(
     *            in="path", name="id",
     *            description="Path to find user by id",
     *            required=true,
     *            @OA\Schema(type="string", format="uuid", example="2a825edc-95f6-49a9-b46a-d2f296e7e1ce")
     *       ),
     *       @OA\Response(response=204, description="No Content")
     *   )
     */
    public function destroy(User $user)
    {
        $user->tokens()->delete();
        $user->delete();
        return response()->noContent();
    }
}
