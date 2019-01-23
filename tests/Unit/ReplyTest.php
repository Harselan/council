<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReplyTest extends TestCase
{
	use DatabaseMigrations;

	#vendor/bin/phpunit tests/Unit/ReplyTest.php

	/** @test */
	function it_has_an_owner()
	{
		$reply = create( 'App\Reply' );

		$this->assertInstanceOf( 'App\User', $reply->owner );
	}

	/** @test */
	function it_knows_if_it_was_just_published()
	{
		$reply = create( 'App\Reply' );

		$this->assertTrue( $reply->wasJustPublished() );

		$reply->created_at = Carbon::now()->subMonth();

		$this->assertFalse( $reply->wasJustPublished() );
	}

	/** @test */
	function it_can_detect_all_mentioned_users_in_the_body()
	{
		$reply = new \App\Reply(
			[
				'body' => '@JaneDoe want to see @JohnDoe'
			] );

		$this->assertEquals( [ 'JaneDoe', 'JohnDoe' ], $reply->mentionedUsers() );
	}

	/** @test */
	function it_wraps_mentioned_usernames_in_the_body_within_anchor_tags()
	{
		$reply = new \App\Reply(
			[
				'body' => 'Hello @Jane-Doe.'
			] );

		$this->assertEquals(
			'Hello <a href="/profiles/Jane-Doe">@Jane-Doe</a>.',
			$reply->body
		);
	}

	/** @test */
	function it_knows_if_it_is_the_best_reply()
	{
		$reply = create( 'App\Reply' );

		$this->assertFalse( $reply->isBest() );

		$reply->thread->update( [ 'best_reply_id' => $reply->id ] );

		$this->assertTrue( $reply->fresh()->isBest() );
	}

	/** @test */
	function a_reply_body_is_sanitized_automatically()
	{
		$reply = make( 'App\Reply', [ 'body' => '<script>alert("bad")</script><p>This is okay.</p>' ] );

		$this->assertEquals( '<p>This is okay.</p>', $reply->body );
	}

	/** @test */
	function a_reply_knows_the_total_xp_earned()
	{
		$this->signIn();

		$reply = create( 'App\Reply' );

		$this->assertEquals( 2, $reply->xp ); // 2 xp for creating reply

		$reply->thread->markBestReply( $reply );

		$this->assertEquals( 52, $reply->xp ); // 50 xp for best reply

		$this->post( route( 'replies.favorite', $reply ) ); // 5 xp for favoriting

		$this->assertEquals( 57, $reply->xp );
	}

	/** @test */
	function it_generates_the_correct_path_for_a_paginated_thread()
	{
		$thread = create( 'App\Thread' );

		$replies = create( 'App\Reply', [ 'thread_id' => $thread->id ], 3 );

		config( [ 'council.pagination.perPage' => 1 ] );

		$this->assertEquals(
			$thread->path() . '?page=1#reply-1',
			$replies->first()->path()
		);

		$this->assertEquals(
			$thread->path() . '?page=2#reply-2',
			$replies[1]->path()
		);

		$this->assertEquals(
			$thread->path() . '?page=3#reply-3',
			$replies->last()->path()
		);
	}
}
