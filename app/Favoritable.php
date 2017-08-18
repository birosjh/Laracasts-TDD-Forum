<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


// Trait created because all of these relate to favoriting something
trait Favoritable
{

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if (! $this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create($attributes);
        }
    }

    public function isFavorited()
    {
        return $this->favorites->where('user_id', auth()->id())->count();
    }

    // This is called an accessor.  You can access it in a view by using the two words in between get and Attribute
    // and putting an underscore between them: $reply->favorites_count
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }


}
