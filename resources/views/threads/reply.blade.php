    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <div class="flex">
                    <a href="/profiles/{{ $reply->owner->name }}">{{ $reply->owner->name }}</a> said {{ $reply->created_at->diffForHumans() }}
                </div>
                <div>
                    <form action="/replies/{{ $reply->id }}/favorites" method="post">
                        {{ csrf_field() }}

                        <button type="submit" class="btn btn-default" name="button" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                            {{ $reply->favorites_count }} {{ str_plural('Favorite', $reply->favorites_count) }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <div class="body">
                {{ $reply->body }}
            </div>
        </div>
    </div>
