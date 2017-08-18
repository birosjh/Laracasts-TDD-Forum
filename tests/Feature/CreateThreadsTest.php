<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guests_may_not_create_threads()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads', [])
            ->assertRedirect('/login');

    }


    /** @test */
    function an_authenticated_user_can_create_new_forum_threads()
    {
        // Given we have a signed in user
        $this->signIn();

        // When we hit the end point to make a new thread
        $thread = make('App\Thread');  // If this were create the data would be persisited

        $response = $this->post('/threads', $thread->toArray());


        // Then when we visit a new page
        $response = $this->get($response->headers->get('Location')) // $response->headers shows us where it was placed after being posted
                    // If we had just used $thread->path() it would have just given us the exact
                    // path of the original and we wouldn't be testing anything
                        ->assertSee($thread->title) // We should see the new thread
                        ->assertSee($thread->body);
    }

    /** @test */
    function a_thread_requires_a_title()
    {

        $this->publishThread(['title' => null])
                ->assertSessionHasErrors('title');

    }

    /** @test */
    function a_thread_requires_a_body()
    {

        $this->publishThread(['body' => null])
                ->assertSessionHasErrors('body');

    }

    /** @test */
    function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel', 2)->create();

        // Check to make sure that it requires channel
        $this->publishThread(['channel_id' => null])
                ->assertSessionHasErrors('channel_id');

        // Check to make sure that it requires the channel to be valid
        $this->publishThread(['channel_id' => 999])
                ->assertSessionHasErrors('channel_id');

    }

    /** @test */
    function unauthorized_users_cannot_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $this->delete($thread->path())
            ->assertRedirect('/login');

        $this->signIn();

        $this->delete($thread->path())
            ->assertStatus(403);


    }

    /** @test */
    function authorized_users_can_delete_threads()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

    }

    function publishThread($overrides = [])
    {
        // Required so that the exception created by validation works.
        $this->withExceptionHandling();

        $this->signIn();

        $thread = make('App\Thread', $overrides);

        return $this->post('/threads', $thread->toArray());
    }
}
