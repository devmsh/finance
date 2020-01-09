<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $guarded = [];

    public function transcations()
    {
        return $this->hasMany(Transaction::class);
    }

    public function addTransaction($data)
    {
        return $this->transcations()->create($data);
    }
}
