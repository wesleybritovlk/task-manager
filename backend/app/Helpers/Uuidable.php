<?php

namespace App\Helpers;

use Illuminate\Support\Str;

trait Uuidable
{
    /**
     * Summary of Uuidable
     * @return void
     */
    public static function bootUuidable(): void
    {
        static::creating(function ($model) {
            $model->id = Str::uuid()->toString();
        });
    }
}
