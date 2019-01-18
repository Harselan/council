<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
	use DatabaseMigrations;
	#vendor/bin/phpunit tests/Feature/FavoritesTest.php

	/** @test */
	public function guests_can_not_favorite_anything()
	{
		$this->withExceptionHandling()
			->post( route( 'replies.favorite', 1 ) )
			->assertRedirect( route('login') );
	}

	/** @test */
	public function an_authenticated_user_can_favorite_any_reply()
	{
		$this->signIn();
		$reply = create( 'App\Reply' );

		$this->post( route( 'replies.favorite', $reply->id ) );

		$this->assertCount( 1, $reply->favorites );
	}

	/** @test */
	public function an_authenticated_user_can_unfavorite_any_reply()
	{
		$this->signIn();
		$reply = create( 'App\Reply' );

		$reply->favorite();

		$this->post( route( 'replies.favorite', $reply->id ) );

		$this->assertCount( 0, $reply->favorites );
	}
}
