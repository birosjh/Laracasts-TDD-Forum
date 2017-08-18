<div class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
            <span class="flex">
                {{ $profileUser->name }} published
                <a href="{{ $activity->subject->path() }}">{{ $activity->subject->title }}</a>
            </span>
        </div>
    </div>

    <div class="panel-body">
        <div class="body">
            {{ $activity->subject->body }}
        </div>
    </div>
</div>
