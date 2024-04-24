<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="TaskResource",
 *     title="TaskResource",
 *     @OA\Property(title="Task", property="data", type="object",
 *          ref="#/components/schemas/Task"
 *     )
 * )
 */
class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'project_id' => $this->project_id,
            'title' => $this->title,
            'description' => $this->description,
            'is_done' => $this->is_done,
        ];
    }
}
