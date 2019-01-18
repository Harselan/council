<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;

	#vendor/bin/phpunit tests/Feature/SubscribeToThreadsTest.php

    /** @test */
    public function a_user_can_subscribe_to_threads()
    {
    	$this->signIn();

        $thread = create( 'App\Thread' );

        $this->post( route( 'threads.subscribe', [ $thread->channel->slug, $thread->slug ] ) );

        $this->assertCount( 1, $thread->fresh()->subscriptions );
    }

	/** @test */
	public function a_user_can_unsubscribe_from_threads()
	{
		$this->signIn();

		$thread = create( 'App\Thread' );

		$thread->subscribe();

		$this->delete( route( 'threads.unsubscribe', [ $thread->channel->slug, $thread->slug ] ) );

		$this->assertCount( 0, $thread->subscriptions );
	}
}
