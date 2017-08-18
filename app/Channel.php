<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    // Makes it return the slug instead of the primary key which is default
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
}
