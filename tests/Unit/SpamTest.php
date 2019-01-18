<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SpamTest extends TestCase
{
	use DatabaseMigrations;

	#vendor.css/bin/phpunit tests/Unit/SpamTest.php

    /** @test */
    public function it_checks_for_invalid_keywords()
    {
    	$this->signIn();

	    $reply = create( 'App\Reply', [ 'body' => 'Innocent reply here' ] );

	    $this->expectException( 'Exception' );

	    $spamReply = create( 'App\Reply', [ 'body' => 'yahoo customer support' ] );
    }

	/** @test */
	public function it_checks_for_any_key_being_down()
	{
		$this->signIn();

		$this->expectException( 'Exception' );

		$reply = create( 'App\Reply', [ 'body' => 'Hello world aaaaaa' ] );
	}
}
