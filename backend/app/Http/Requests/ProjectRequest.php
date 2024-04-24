<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="ProjectRequest",
 *     title="ProjectRequest",
 *     @OA\Property(property="title", type="string", minLength=3, maxLength=100),
 * )
 */
class ProjectRequest extends FormRequest
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
        $this->only('title');
        return [
            'title' => ['required', 'string', 'min:3', 'max:100'],
        ];
    }
}
