<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\Task\TaskCollection;
use App\Http\Resources\Task\TaskResource;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     tags={"Task Endpoint"},
     *     summary="Get list of tasks",
     *     description="Returns list paginated of tasks",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *          in="query",
     *          name="q",
     *          description="Query to search tasks by title",
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
     *     @OA\Parameter(
     *           in="query",
     *           name="is_done",
     *           description="Query to filter completed tasks",
     *           @OA\Schema(type="boolean", example="false"),
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/TaskCollection")
     *     )
     * )
     */
    public function index(Request $request): TaskCollection
    {
        $buildTasks = $this->queryBuilder(Task::query(), $request);
        return new TaskCollection($buildTasks->paginate());
    }

    protected function queryBuilder(Builder $query, Request $request): Builder
    {
        if ($request->has('q')) $query
            ->where('title', 'like', '%' . $request->q . '%')
            ->orWhere('desc', 'like', '%' . $request->q . '%');
        if ($request->has('sort')) $query
            ->orderBy('created_at', $request->sort);
        if ($request->has('is_done')) {
            $is_done = $request->is_done === "true";
            $query->where('is_done', $is_done);
        }
        return $query;
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     tags={"Task Endpoint"},
     *     summary="Get task info by id",
     *     description="Returns task data by id",
     *     security={{"bearer_token"={}}},
     *     @OA\Parameter(
     *           in="path",
     *           name="id",
     *           description="Path to find task by id",
     *           required=true,
     *           @OA\Schema(type="string", format="uuid", example="2a825edc-95f6-49a9-b46a-d2f296e7e1ce")
     *      ),
     *      @OA\Response(
     *           response=200,
     *           description="OK",
     *           @OA\JsonContent(ref="#/components/schemas/TaskResource")
     *      )
     * )
     */
    public function show(Task $task): TaskResource
    {
        return new TaskResource($task);
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     tags={"Task Endpoint"},
     *     summary="Post a new Task",
     *     description="Returns id of the created task",
     *     security={{"bearer_token"={}}},
     *     @OA\RequestBody(
     *           @OA\MediaType(
     *               mediaType="application/json",
     *               @OA\Schema(ref="#/components/schemas/TaskRequest")
     *           )
     *      ),
     *      @OA\Response(
     *           response=201, description="Created",
     *           @OA\JsonContent(
     *               oneOf={
     *                   @OA\Schema(
     *                       @OA\Property(property="message", type="string", example="Task created successfully!"),
     *                       @OA\Property(property="data", type="object",
     *                           @OA\Property(property="id", type="string", format="uuid")
     *                       )
     *                   )
     *               }
     *           )
     *      )
     * )
     */
    public function store(TaskRequest $request)
    {
        $validated = $request->validated();
        $create = Auth::user()->tasks()->create($validated);
        return response()->json([
            "message" => "Task created successfully!",
            "data" => ['id' => $create->id]
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     tags={"Task Endpoint"},
     *     summary="Put task by id",
     *     description="Returns id of the updated task",
     *     security={{"bearer_token"={}}},
     *     @OA\Parameter(
     *           in="path", name="id",
     *           description="Path to find task by id",
     *           required=true,
     *           @OA\Schema(type="string", format="uuid", example="2a825edc-95f6-49a9-b46a-d2f296e7e1ce")
     *      ),
     *      @OA\RequestBody(
     *           @OA\MediaType(
     *               mediaType="application/json",
     *               @OA\Schema(ref="#/components/schemas/TaskRequest")
     *           )
     *      ),
     *      @OA\Response(
     *           response=200, description="Ok",
     *           @OA\JsonContent(
     *               oneOf={
     *                   @OA\Schema(
     *                       @OA\Property(property="message", type="string", example="Task updated successfully!"),
     *                       @OA\Property(property="data", type="object",
     *                           @OA\Property(property="id", type="string", format="uuid")
     *                       )
     *                   )
     *               }
     *           )
     *      )
     * )
     */
    public function update(TaskRequest $request, Task $task)
    {
        $id = $task->id;
        $validated = $request->validated();
        Task::query()->where('id', $id)->update($validated);
        return response()->json([
            "message" => "Task updated successfully!",
            "data" => ['id' => $id]
        ]);
    }

    /**
     * @OA\Delete(
     *      path="/api/tasks/{id}",
     *      tags={"Task Endpoint"},
     *      summary="Delete task by id",
     *      security={{"bearer_token"={}}},
     *      @OA\Parameter(
     *           in="path", name="id",
     *           description="Path to find task by id",
     *           required=true,
     *           @OA\Schema(type="string", format="uuid", example="2a825edc-95f6-49a9-b46a-d2f296e7e1ce")
     *      ),
     *      @OA\Response(response=204, description="No Content")
     *  )
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->noContent();
    }
}
