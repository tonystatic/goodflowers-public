<?php

declare(strict_types=1);

namespace App\Support\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Trait HasUuid.
 * @package App\Support\Models
 */
trait HasUuid
{
    public function getIncrementing() : bool
    {
        return false;
    }

    protected static function bootHasUuid() : void
    {
        static::creating(function (Model $model) : void {
            if (! isset($model->attributes[$model->getKeyName()])) {
                $model->incrementing = false;
                $model->attributes[$model->getKeyName()] = (string) Str::orderedUuid();
            }
        });
    }
}
