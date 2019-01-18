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

	    $this->assertEquals( Reputation::THREAD_WAS_PUBLISHED, $thread->creator->reputation );
    }

	/** @test */
	function a_user_loose_points_when_they_delete_a_thread()
	{
		$this->signIn();

		$thread = create( 'App\Thread', [ 'user_id' => auth()->id() ] );

		$this->assertEquals( Reputation::THREAD_WAS_PUBLISHED, $thread->creator->reputation );

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

		$this->assertEquals( Reputation::REPLY_POSTED, $reply->owner->reputation );
	}

	/** @test */
	function a_user_loose_points_when_their_reply_to_a_thread_is_deleted()
	{
		$this->signIn();

		$reply = create( 'App\Reply', [ 'user_id' => auth()->id() ] );

		$this->assertEquals( Reputation::REPLY_POSTED, $reply->owner->reputation );

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

		$total = Reputation::REPLY_POSTED + Reputation::BEST_REPLY_AWARDED;

		$this->assertEquals( $total, $reply->owner->reputation );
	}

	/** @test */
	function a_user_earns_points_when_their_reply_is_favorited()
	{
		$this->signIn();

		$thread = create('App\Thread');

		$reply = $thread->addReply([
			'user_id'   => auth()->id(),
			'body'      => 'Here is a reply.'
		]);

		$this->post( route( 'replies.favorite', $reply ) );

		$total = Reputation::REPLY_POSTED + Reputation::REPLY_FAVORITED;

		$this->assertEquals( $total, $reply->owner->fresh()->reputation );
	}

	/** @test */
	function a_user_looses_points_when_their_favorited_reply_is_unfavorited()
	{
		$this->signIn();

		$reply = create( 'App\Reply', [ 'user_id' => auth()->id() ] );

		$this->post( route( 'replies.favorite', $reply ) );

		$total = Reputation::REPLY_POSTED + Reputation::REPLY_FAVORITED;

		$this->assertEquals( $total, $reply->owner->fresh()->reputation );

		$this->post( route( 'replies.favorite', $reply ) );

		$total = Reputation::REPLY_POSTED + Reputation::REPLY_FAVORITED - Reputation::REPLY_FAVORITED;

		$this->assertEquals( $total, $reply->owner->fresh()->reputation );
	}
}
