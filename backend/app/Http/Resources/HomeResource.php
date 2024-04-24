<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "name" => "Task-Manager API",
            "repository" => "https://github.com/wesleybritovlk/task-manager",
            "documentation" => "/api/documentation"
        ];
    }
}
