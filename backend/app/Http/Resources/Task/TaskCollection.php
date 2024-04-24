<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     schema="TaskCollection",
 *     title="TaskCollection",
 *     @OA\Property(property="data", type="array",
 *          @OA\Items(title="Task", type="object",
 *              ref="#/components/schemas/Task"
 *          )
 *     )
 * )
 */
class TaskCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
