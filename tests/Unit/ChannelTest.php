<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChannelTest extends TestCase
{
	use DatabaseMigrations;

	#vendor/bin/phpunit tests/Unit/ChannelTest.php

	/** @test */
	function a_channel_consists_of_threads()
	{
		$channel    = create( 'App\Channel' );
		$thread     = create( 'App\Thread', [ 'channel_id' => $channel->id ] );

		$this->assertTrue( $channel->threads->contains( $thread ) );
	}

	/** @test */
	function a_channel_can_be_archived()
	{
		$channel = create( 'App\Channel' );

		$this->assertFalse( $channel->archived );

		$channel->archive();

		$this->assertTrue( $channel->fresh()->archived );
	}
}
