<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;

	#vendor/bin/phpunit tests/Feature/ProfilesTest.php

    /** @test */
    public function a_user_has_a_profile()
    {
    	$user = create( 'App\User' );

    	$this->get( route( 'profile', $user->name ) )
	    ->assertSee( $user->name );
    }

    /** @test */
    public function profiles_display_all_threads_created_by_the_associated_user()
    {
    	$this->signIn();

	    $thread = create( 'App\Thread', [ 'user_id' => auth()->id() ] );

	    $this->get( route( 'profile', auth()->user()->name ) )
		    ->assertSee( $thread->title )
		    ->assertSee( $thread->body );
    }
}