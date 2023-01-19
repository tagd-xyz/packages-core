<?php

namespace Tagd\Core\Models\Traits;

use Illuminate\Support\Str;

trait HasUuidKey
{
    /**
     * Call this from your model
     *
     * protected static function boot()
     * {
     *     parent::boot();
     *     static::autoUuidKey();
     * }
     *
     * @return void
     */
    public static function autoUuidKey()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::orderedUuid()->toString();
            }
        });
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        // even we're using orderedUuid(), we set this to false
        // so the getKeyType() as string takes effect
        return false;
    }

    /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }
}
