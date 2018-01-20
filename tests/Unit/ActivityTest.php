<?php


namespace Tests\Feature;

use App\Thread;
use App\Activity;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class Test extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_records_activity_when_a_thread_is_created()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => 'App\Thread'
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    function it_records_activity_when_a_reply_is_created()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $this->assertEquals(2, Activity::count());
    }

    /** @test */
    function it_fetches_an_activity_feed_for_any_user() {
        // Given we have a thread
        $this->signIn();
        create('App\Thread', [ 'user_id' => auth()->id() ]);
        // and another thread from a week ago
        create('App\Thread', [ 'user_id' => auth()->id(), 'created_at' => Carbon::now()->subWeek() ]);
        // When we fetch their feed
        $feed = Activity::feed(auth()->user());
        // Then it should be returned in the proper format.
        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));
    }

}
