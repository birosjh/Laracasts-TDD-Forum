<?php

namespace App\Http\Controllers;

use App\Thread; //Don't forget to add these if you are going to be using an object of that type
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    /*
    * Create a new Replies Controller
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
    * Persist a new reply
    *
    * @param $channelId
    * @param Thread $thread
    * @return \Illuminate\Http\RedirectResponse
    */
    public function store($channelId, Thread $thread)
    {
        $this->validate(request(), [
            'body' => 'required'
        ]);

        $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        return back();

    }
}
