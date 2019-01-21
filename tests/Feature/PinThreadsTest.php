<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PinThreadsTest extends TestCase
{
    use DatabaseMigrations;

    #vendor/bin/phpunit tests/Feature/PinThreadsTest.php

    /** @test */
    function administrators_can_pin_threads()
    {
    	$this->signInAdmin();

    	$thread = create( 'App\Thread' );

    	$this->post( route( 'pinned-threads.store', $thread ) );

    	$this->assertTrue( $thread->fresh()->pinned, 'Failed asserting that the thread was pinned.' );
    }

    /** @test */
    function administrators_can_unpin_threads()
    {
    	$this->signInAdmin();

    	$thread = create( 'App\Thread', [ 'pinned' => true ] );

    	$this->delete( route( 'pinned-threads.destroy', $thread ) );

    	$this->assertFalse( $thread->fresh()->pinned, 'Failed asserting that the thread was unpinned.' );
    }

    /** @test */
	function pinned_threads_are_listed_first()
	{
		$this->signInAdmin();


		$threads = create( 'App\Thread', [], 3 );
		$ids     = $threads->pluck('id');

		$this->getJson( route( 'threads.index' ) )
			->assertJson([
			'data' => [
				[ 'id' => $ids[0] ],
				[ 'id' => $ids[1] ],
				[ 'id' => $ids[2] ],
			]
		]);

		$this->post( route( 'pinned-threads.store', $pinned = $threads->last() ) );

		$this->getJson( route( 'threads.index' ) )
			->assertJson([
			'data' => [
				[ 'id' => $pinned->id ],
				[ 'id' => $ids[0] ],
				[ 'id' => $ids[1] ],
			]
		]);
	}
}
