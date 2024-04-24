<?php

namespace App\Http\Resources\Project;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     schema="ProjectCollection",
 *     title="ProjectCollection",
 *     @OA\Property(property="data", type="array",
 *          @OA\Items(title="Project", type="object",
 *              ref="#/components/schemas/Project"
 *          )
 *     )
 * )
 */
class ProjectCollection extends ResourceCollection
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
