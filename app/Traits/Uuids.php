<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Uuids
{
   /**
     * Override boot function from Laravel to use UUID.
     * Str facade to generate UUID and then convert it to string.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }

   /**
     * Indicates false about if IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

   /**
     * Specify that the primary key is a string.
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }
}