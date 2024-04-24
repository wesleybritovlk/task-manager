<?php

namespace App\Models;

use App\Helpers\Uuidable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Schema(
 *     schema="Task",
 *     title="Task",
 *     @OA\Property(property="id", type="string", format="uuid"),
 *     @OA\Property(property="user_id", type="string", format="uuid"),
 *     @OA\Property(property="project_id", type="string", format="uuid"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="is_done", type="boolean"),
 * )
 */
class Task extends Model
{
    use Uuidable, HasFactory;

    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $casts = [
        'is_done' => 'boolean'
    ];

    protected $fillable = [
        'title',
        'description',
        'is_done',
        'project_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(User::class, 'project_id');
    }

    protected static function booted(): void
    {
        static::addGlobalScope('user', function (Builder $builder) {
            $builder->where('user_id', Auth::id());
        });
    }
}
