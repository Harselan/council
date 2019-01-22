<?php
namespace Tests\Feature;

use Illuminate\Support\Carbon;
use App\Activity;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    #vendor.css/bin/phpunit tests/Unit/ActivityTest.php

    /** @test */
    function it_records_activity_when_a_thread_is_created()
    {
    	$this->signIn();

        $thread = create( 'App\Thread' );

        $this->assertDatabaseHas( 'activities', [
        	'type' => 'created_thread',
	        'user_id' => auth()->id(),
			'subject_id' => $thread->id,
	        'subject_type' => 'App\Thread'
        ] );

        $activity = Activity::first();

	    $this->assertEquals( $activity->subject->id, $thread->id );
    }

    /** @test */
    function it_records_activity_when_a_reply_is_created()
    {
    	$this->signIn();

    	create( 'App\Reply' );

    	$this->assertEquals( 2, Activity::count() );
    }

    /** @test */
    function it_fetches_a_feed_for_any_user()
    {
	    $this->signIn();

	    create( 'App\Thread', [ 'user_id' => auth()->id() ], 3 );

		auth()->user()->activity()->first()->update( [ 'created_at' => Carbon::now()->subWeek() ] );

	    $feed = Activity::feed( auth()->user() );

	    $this->assertCount( 3, $feed->all() );
	    $this->assertEquals( [ 1, 1, 1 ], $feed->pluck('user_id')->toArray() );
    }
}
