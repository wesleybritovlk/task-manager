<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="LoginRequest",
 *     title="LoginRequest",
 *     @OA\Property(property="email", type="string", format="email"),
 *     @OA\Property(property="password", type="string", minLength=12),
 * )
 */
class LoginRequest extends FormRequest
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
        $this->only('email', 'password');
        return [
            'email' => ['required', 'email'],
            'password' => 'required'
        ];
    }
}
