<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public function getRouteKeyName()
    {
        return 'name';  // may change into a user name
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
        // You could write Thread::class or 'App\Class' and both would be valid, but by writing
        // Thread::class PHP knows at compile time whether the class exists.  For 'App\Class' you
        // don't find out till run time.
    }

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }
}
