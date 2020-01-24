<?php

namespace App;

/**
 * Class Model.
 * @property string $uuid
 */
class Model extends \Illuminate\Database\Eloquent\Model
{
    /**
     * A helper method to quickly retrieve an account by uuid.
     * @param string $uuid
     * @return Model|null
     */
    public static function uuid(string $uuid): ?self
    {
        return static::where('uuid', $uuid)->first();
    }
}
