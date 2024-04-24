<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="RegisterRequest",
 *     title="RegisterRequest",
 *     @OA\Property(property="name", type="string", minLength=3),
 *     @OA\Property(property="email", type="string", format="email"),
 *     @OA\Property(property="password", type="string", minLength=12),
 *     @OA\Property(property="password_confirmation", type="string", minLength=12),
 * )
 */
class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $this->only('name', 'email', 'password');
        return [
            'name' => ['required', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', 'min:12', 'max:32']
        ];
    }
}
