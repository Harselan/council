<?php
namespace Tests\Feature;

use App\Reputation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReputationTest extends TestCase
{
    use RefreshDatabase;

	#vendor/bin/phpunit tests/Feature/ReputationTest.php

    /** @test */
    function a_user_earns_points_when_they_create_a_thread()
    {
	    $thread = create('App\Thread');

	    $this->assertEquals( config('council.reputation.thread_was_published'), $thread->creator->reputation );
    }

	/** @test */
	function a_user_loose_points_when_they_delete_a_thread()
	{
		$this->signIn();

		$thread = create( 'App\Thread', [ 'user_id' => auth()->id() ] );

		$this->assertEquals( config('council.reputation.thread_was_published'), $thread->creator->reputation );

		$this->delete( $thread->path() );

		$this->assertEquals( 0, $thread->creator->fresh()->reputation );
	}

    /** @test */
	function a_user_earns_points_when_they_reply_to_a_thread()
	{
		$thread = create('App\Thread');

		$reply = $thread->addReply([
			'user_id'   => create( 'App\User' )->id,
			'body'      => 'Here is a reply.'
		]);

		$this->assertEquals( config('council.reputation.reply_posted'), $reply->owner->reputation );
	}

	/** @test */
	function a_user_loose_points_when_their_reply_to_a_thread_is_deleted()
	{
		$this->signIn();

		$reply = create( 'App\Reply', [ 'user_id' => auth()->id() ] );

		$this->assertEquals( config('council.reputation.reply_posted'), $reply->owner->reputation );

		$this->delete( route( 'replies.destroy', $reply ) );

		$this->assertEquals( 0, $reply->owner->fresh()->reputation );
	}

	/** @test */
	function a_user_earns_points_when_their_reply_is_marked_as_best()
	{
		$thread = create('App\Thread');

		$reply = $thread->addReply([
			'user_id'   => create( 'App\User' )->id,
			'body'      => 'Here is a reply.'
		]);

		$reply->thread->markBestReply( $reply );

		$total = config('council.reputation.reply_posted') + config('council.reputation.best_reply_awarded');

		$this->assertEquals( $total, $reply->owner->reputation );
	}

	/** @test */
	function a_user_earns_points_when_their_reply_is_favorited()
	{
		// Given we have a signed in user, John.
		$this->signIn( $john = create( 'App\User' ) );

		// And also Jane...
		$jane = create( 'App\User' );

		// If Jane adds a new reply to a thread...
		$reply = create('App\Thread')->addReply([
			'user_id'   => $jane->id,
			'body'      => 'Here is a reply.'
		]);

		// And John favorites that reply
		$this->post( route( 'replies.favorite', $reply ) );

		// Then Jane's reputation should grow, accordingly.
		$this->assertEquals(
			config('council.reputation.reply_posted') + config( 'council.reputation.reply_favorited' ),
			$jane->fresh()->reputation
		);

		// While John's reputation remain unaffected.
		$this->assertEquals( 0, $john->fresh()->reputation );
	}

	/** @test */
	function a_user_looses_points_when_their_favorited_reply_is_unfavorited()
	{
		// Given we have a signed in user, John.
		$this->signIn( $john = create( 'App\User' ) );

		// And also Jane...
		$jane  = create( 'App\User' );

		// If Jane adds a new reply to a thread...
		$reply = create( 'App\Reply', [ 'user_id' => $jane->id ] );

		// And John favorites that reply
		$this->post( route( 'replies.favorite', $reply ) );

		// Then Jane's reputation should grow, accordingly.
		$this->assertEquals(
			config('council.reputation.reply_posted') + config( 'council.reputation.reply_favorited' ),
			$jane->fresh()->reputation
		);

		// But, if John unfavorites that reply...
		$this->post( route( 'replies.favorite', $reply ) );

		// Then Jane's reputation should be reduced, accordingly.
		$this->assertEquals( config( 'council.reputation.reply_posted' ), $jane->fresh()->reputation );

		// While John's reputation remain unaffected.
		$this->assertEquals( 0, $john->fresh()->reputation );
	}
}
