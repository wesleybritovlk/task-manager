<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UserRequest",
 *     title="UserRequest",
 *     @OA\Property(property="name", type="string", minLength=3, maxLength=100),
 *     @OA\Property(property="email", type="string",format="email"),
 * )
 */
class UserRequest extends FormRequest
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
        $this->only('name', 'email');
        return [
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email']
        ];
    }
}
