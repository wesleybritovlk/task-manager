<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\Project\ProjectCollection;
use App\Http\Resources\Project\ProjectResource;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/projects",
     *     tags={"Project Endpoint"},
     *     summary="Get list of projects",
     *     description="Returns list paginated of projects",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *          in="query",
     *          name="q",
     *          description="Query to search projects by title",
     *          @OA\Schema(type="string", example="title")
     *     ),
     *     @OA\Parameter(
     *          in="query",
     *          name="sort",
     *          description="Query to sort by creation: asc|desc",
     *          @OA\Schema(type="string"),
     *          @OA\Examples(example="asc", value="asc", summary="An asc sort --default"),
     *          @OA\Examples(example="desc", value="desc", summary="An desc sort")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/ProjectCollection")
     *     )
     * )
     */
    public function index(Request $request): ProjectCollection
    {
        $buildProjects = $this->queryBuilder(Project::query(), $request);
        return new ProjectCollection($buildProjects->paginate());
    }

    protected function queryBuilder(Builder $query, Request $request): Builder
    {
        if ($request->has('q')) $query->where('title', 'like', '%' . $request->q . '%');
        if ($request->has('sort')) $query->orderBy('created_at', $request->sort);
        return $query;
    }

    /**
     * @OA\Get(
     *     path="/api/projects/{id}",
     *     tags={"Project Endpoint"},
     *     summary="Get project info by id",
     *     description="Returns project data by id",
     *     security={{"bearer_token"={}}},
     *     @OA\Parameter(
     *          in="path",
     *          name="id",
     *          description="Path to find project by id",
     *          required=true,
     *          @OA\Schema(type="string", format="uuid", example="2a825edc-95f6-49a9-b46a-d2f296e7e1ce")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/ProjectResource")
     *     )
     * )
     * */
    public function show(Project $project): ProjectResource
    {
        return new ProjectResource($project);
    }

    /**
     * @OA\Post(
     *     path="/api/projects",
     *     tags={"Project Endpoint"},
     *     summary="Post a new Project",
     *     description="Returns id of the created project",
     *     security={{"bearer_token"={}}},
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/ProjectRequest")
     *          )
     *     ),
     *     @OA\Response(
     *          response=201, description="Created",
     *          @OA\JsonContent(
     *              oneOf={
     *                  @OA\Schema(
     *                      @OA\Property(property="message", type="string", example="Project created successfully!"),
     *                      @OA\Property(property="data", type="object",
     *                          @OA\Property(property="id", type="string", format="uuid")
     *                      )
     *                  )
     *              }
     *          )
     *     )
     * )
     */
    public function store(ProjectRequest $request)
    {
        $validated = $request->validated();
        $create = Auth::user()->projects()->create($validated);
        return response()->json(["message" => "Project created successfully!", "data" => ["id" => $create->id]], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/projects/{id}",
     *     tags={"Project Endpoint"},
     *     summary="Put project by id",
     *     description="Returns id of the updated project",
     *     security={{"bearer_token"={}}},
     *     @OA\Parameter(
     *          in="path", name="id",
     *          description="Path to find project by id",
     *          required=true,
     *          @OA\Schema(type="string", format="uuid", example="2a825edc-95f6-49a9-b46a-d2f296e7e1ce")
     *     ),
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/ProjectRequest")
     *          )
     *     ),
     *     @OA\Response(
     *          response=200, description="Ok",
     *          @OA\JsonContent(
     *              oneOf={
     *                  @OA\Schema(
     *                      @OA\Property(property="message", type="string", example="Project updated successfully!"),
     *                      @OA\Property(property="data", type="object",
     *                          @OA\Property(property="id", type="string", format="uuid")
     *                      )
     *                  )
     *              }
     *          )
     *     )
     * )
     */
    public function update(ProjectRequest $request, Project $project)
    {
        $id = $project->id;
        $validated = $request->validated();
        Project::query()->where('id', $id)->update($validated);
        return response()->json(["message" => "Project updated successfully!", "data" => ["id" => $id]]);
    }

    /**
     * @OA\Delete(
     *     path="/api/projects/{id}",
     *     tags={"Project Endpoint"},
     *     summary="Delete project by id",
     *     security={{"bearer_token"={}}},
     *     @OA\Parameter(
     *          in="path", name="id",
     *          description="Path to find project by id",
     *          required=true,
     *          @OA\Schema(type="string", format="uuid", example="2a825edc-95f6-49a9-b46a-d2f296e7e1ce")
     *     ),
     *     @OA\Response(response=204, description="No Content")
     * )
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return response()->noContent();
    }
}
