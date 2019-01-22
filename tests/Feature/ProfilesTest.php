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

    	$this->get( route( 'profile', $user ) )
	    ->assertSee( $user->name );
    }
}
