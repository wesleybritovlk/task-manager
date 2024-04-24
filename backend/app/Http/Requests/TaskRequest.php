<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="TaskRequest",
 *     title="TaskRequest",
 *     @OA\Property(property="project_id", type="string", format="uuid"),
 *     @OA\Property(property="title", type="string", minLength=3, maxLength=100),
 *     @OA\Property(property="description", type="string", maxLength=255),
 *     @OA\Property(property="is_done", type="boolean", example=false),
 * )
 */
class TaskRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $this->only('project_id' . 'title', 'description', 'is_done');
        $exists = Rule::exists('projects', 'id')
            ->where(function ($query) {
                $query->where('user_id', Auth::id());
            });
        return [
            'project_id' => ['nullable', 'uuid', $exists],
            'title' => ['required', 'string', 'min:3', 'max:100'],
            'description' => ['required', 'string', 'max:255'],
            'is_done' => ['required', 'boolean']
        ];
    }
}
