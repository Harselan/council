<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateThreadsTest extends TestCase
{
	use RefreshDatabase;

	#vendor/bin/phpunit tests/Feature/UpdateThreadsTest.php

	public function setUp()
	{
		parent::setUp();

		$this->withExceptionHandling();
		$this->signIn();
	}

	/** @test */
	function unauthorized_users_may_not_update_threads()
	{
		$thread = create( 'App\Thread', [ 'user_id' => create( 'App\User' )->id ] );

		$this->patch( route( 'threads.update', [ $thread->channel, $thread ] ), [] )->assertStatus(403);
	}

	/** @test */
	function a_thread_requires_a_title_and_body_to_be_updated()
	{
		$thread = create( 'App\Thread', [ 'user_id' => auth()->id() ] );

		$this->patch( route( 'threads.update', [ $thread->channel, $thread ] ), [
			'title' => 'Changed'
		] )->assertSessionHasErrors('body');

		$this->patch( route( 'threads.update', [ $thread->channel, $thread ] ), [
			'body' => 'Changed'
		] )->assertSessionHasErrors('title');
	}

	/** @test */
	function a_thread_can_be_updated_by_its_creator()
	{
		$thread = create( 'App\Thread', [ 'user_id' => auth()->id() ] );

		$this->patch( route( 'threads.update', [ $thread->channel, $thread ] ), [
			'title' => 'Changed',
			'body' => 'Changed body.'
		] );

		$thread = $thread->fresh();

		$this->assertEquals( 'Changed', $thread->title );
		$this->assertEquals( 'Changed body.', $thread->body );
	}
}
