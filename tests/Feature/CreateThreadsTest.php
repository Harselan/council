<?php

namespace Tests\Feature;

use App\Rules\Recaptcha;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
	use DatabaseMigrations;

	#vendor/bin/phpunit tests/Feature/CreateThreadsTest.php

	public function setUp()
	{
		parent::setUp();

		app()->singleton( Recaptcha::class, function ()
		{
			return \Mockery::mock( Recaptcha::class, function( $mockery )
			{
				$mockery->shouldReceive( 'passes' )->andReturn( true );
			} );
		} );
	}

	/** @test */
    function guests_may_not_create_threads()
    {
	    $this->withExceptionHandling();

	    $this->get( route( 'threads.create' ) )
		    ->assertRedirect( route( 'login' ) );

	    $this->post( route( 'threads.store' ) )
		    ->assertRedirect( route( 'login' ) );
    }

    /** @test */
    function new_users_must_first_confirm_their_email_address_before_creating_threads()
    {
    	$user = factory( 'App\User' )->state( 'unconfirmed' )->create();

	    $this->signIn( $user );

	    $thread = make( 'App\Thread' );

	    $this->post( route( 'threads.store', $thread->toArray() ) )
		    ->assertRedirect( route( 'threads.index' ) )
		    ->assertSessionHas( 'flash', 'You must first confirm your email address.' );
    }

	/** @test */
    function a_user_can_create_new_forum_threads()
    {
	    $title    = 'some title';
	    $body     = 'some body';

	    $response = $this->publishThread( [ 'title' => $title, 'body' => $body ] );

	    $this->get( $response->headers->get('Location') )
	    ->assertSee( $title )
	    ->assertSee( $body );
    }

    /** @test */
    function a_thread_requires_a_title()
    {
    	$this->publishThread( ['title' => null] )
		    ->assertSessionHasErrors('title');
    }

	/** @test */
	function a_thread_requires_a_body()
	{
		$this->publishThread( ['body' => null] )
			->assertSessionHasErrors('body');
	}

	/** @test */
	function a_thread_requires_recaptcha_verification()
	{
		unset( app()[Recaptcha::class] );

		$this->publishThread( [ 'g-recaptcha-response' => 'test' ] )
			->assertSessionHasErrors( 'g-recaptcha-response' );
	}

	/** @test */
	function a_thread_requires_a_valid_channel()
	{
		factory( 'App\Channel', 2 )->create();

		$this->publishThread( ['channel_id' => null] )
			->assertSessionHasErrors('channel_id');

		$this->publishThread( ['channel_id' => 999] )
			->assertSessionHasErrors('channel_id');
	}

	/** @test */
	function a_thread_requires_a_unique_slug()
	{
		$this->signIn();

		$thread = create( 'App\Thread', [ 'title' => 'Foo Title' ] );

		$this->assertEquals( $thread->fresh()->slug, 'foo-title' );

		$thread = $this->postJson( route( 'threads.store' ), $thread->toArray() + [ 'g-recaptcha-response' => 'test' ] )->json();

		$this->assertEquals( "foo-title-{$thread['id']}", $thread['slug'] );
	}

	/** @test */
	function a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug()
	{
		$this->signIn();

		$thread = create( 'App\Thread', [ 'title' => 'Some Title 24' ] );

		$thread = $this->postJson( route( 'threads.store' ), $thread->toArray() + [ 'g-recaptcha-response' => 'test' ] )->json();

		$this->assertEquals( "some-title-24-{$thread['id']}", $thread['slug'] );
	}

    function publishThread( $overrides = [] )
    {
	    $this->withExceptionHandling()->signIn();

	    $thread = make( 'App\Thread', $overrides );

	    return $this->post( route( 'threads.store', $thread->toArray() + [ 'g-recaptcha-response' => 'test' ] ) );
    }

    /** @test */
    function unauthorized_users_may_not_delete_threads()
    {
    	$this->withExceptionHandling();

	    $thread = create( 'App\Thread' );

	    $this->delete( route( 'threads.destroy', [ $thread->channel->slug, $thread->slug ] ) )
		    ->assertRedirect( route( 'login' ) );

	    $this->signIn();
	    $this->delete( route( 'threads.destroy', [ $thread->channel->slug, $thread->slug ] ) )->assertStatus( 403 );
    }

    /** @test */
    function authorized_users_can_delete_thread()
    {
		$this->signIn();

		$thread = create( 'App\Thread', [ 'user_id' => auth()->id() ] );
		$reply  = create( 'App\Reply', [ 'thread_id' => $thread->id ] );

		$response = $this->json( 'DELETE', route( 'threads.destroy', [ $thread->channel->slug, $thread->slug ] ) );

	    $response->assertStatus( 204 );

		$this->assertDatabaseMissing( 'threads', [ 'id' => $thread->id ] );
	    $this->assertDatabaseMissing( 'replies', [ 'id' => $reply->id ] );

	    $this->assertDatabaseMissing( 'activities', [
		    'subject_id'    => $reply->id,
		    'subject_type'  => get_class( $reply )
	    ] );

	    $this->assertDatabaseMissing( 'activities', [
	    	'subject_id'    => $thread->id,
		    'subject_type'  => get_class( $thread )
	    ] );
    }
}
