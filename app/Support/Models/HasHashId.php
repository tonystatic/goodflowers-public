<?php

declare(strict_types=1);

namespace App\Support\Models;

use App\Support\HashId\Generator;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasUuid.
 * @package App\Support\Models
 */
trait HasHashId
{
    protected static function bootHasHashId() : void
    {
        static::created(function (Model $model) : void {
            $timestampsValue = $model->timestamps;
            $model->timestamps = false;
            $model->setAttribute(
                static::getHashIdField(),
                static::generateHashIdById((int) $model->getKey())
            );
            $model->save();
            $model->timestamps = $timestampsValue;
        });
    }

    /**
     * @param int $id
     * @return string
     */
    public static function generateHashIdById(int $id) : string
    {
        /** @var \App\Support\HashId\Generator $generator */
        $generator = app(Generator::class);

        return $generator->generate($id, static::getHashIdMinLength());
    }

    /**
     * @return string
     */
    public static function getHashIdField() : string
    {
        return static::$hashIdField ?? 'hash_id';
    }

    /**
     * @return int
     */
    public static function getHashIdMinLength() : int
    {
        return isset(static::$hashIdMinLength) && \is_int(static::$hashIdMinLength)
            ? static::$hashIdMinLength
            : 8;
    }
}
