<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guests_cannot_favorite_anything()
    {
        $this->withExceptionHandling();
        $this->post('replies/1/favorites')
            ->assertRedirect('/login');


    }

    /** @test */
    function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');  // It also creates a thread in the process

        // If I post to a "favorte" endpoint
        $this->post('replies/' . $reply->id . '/favorites');

        // IT should be recorded in the database
        $this->assertCount(1, $reply->favorites);

    }

    /** @test */
    function an_authenticated_user_can_only_favorite_a_reply_once()
    {
        $this->signIn();

        $reply = create('App\Reply');

        // If I try to favorite something twice
        try {
            $this->post('replies/' . $reply->id . '/favorites');
            $this->post('replies/' . $reply->id . '/favorites');
        }
        catch (\Exception $e) {
            $this->fail('Did not expect to insert the same record twice');
        }


        // It should ignore the second favorite and still only show one favorite
        $this->assertCount(1, $reply->favorites);

    }

}
