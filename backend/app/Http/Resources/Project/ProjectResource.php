<?php

namespace App\Http\Resources\Project;

use App\Http\Resources\Task\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ProjectResource",
 *     title="ProjectResource",
 *     @OA\Property(title="Project", property="data", type="object",
 *          ref="#/components/schemas/Project"
 *     )
 * )
 */
class ProjectResource extends JsonResource
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
            'title' => $this->title,
            'tasks' => $this->tasks->map(fn(Task $task) => $task->id),
        ];
    }
}
