<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="Task Manager API",
 *     @OA\Contact(
 *         email="wesleymuniz20@gmail.com"
 *     ),
 *     @OA\License(
 *          name="MIT",
 *          url="https://mit-license.org"
 *     )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
