<?php

namespace App;

class Model extends \Illuminate\Database\Eloquent\Model
{
    /**
     * A helper method to quickly retrieve an account by uuid.
     */
    public static function uuid(string $uuid): ?self
    {
        return static::where('uuid', $uuid)->first();
    }
}
