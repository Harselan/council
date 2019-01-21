<?php
namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddAvatarTest extends TestCase
{
    use DatabaseMigrations;

	#vendor/bin/phpunit tests/Feature/AddAvatarTest.php

    /** @test */
    function only_members_can_add_avatars()
    {
    	$this->withExceptionHandling();

        $this->json( 'POST', route( 'avatar', 1 ) )
	        ->assertStatus( 401 );
    }

    /** @test */
	function a_valid_avatar_must_be_provided()
	{
		$this->withExceptionHandling()->signIn();

		$this->json( 'POST', route( 'avatar', auth()->id() ), [
			'avatar' => 'not-a-valid-image'
		] )->assertStatus( 422 );
	}

	/** @test */
	function a_user_may_add_an_avatar_to_their_profile()
	{
		$this->withExceptionHandling();
		$this->signIn();

		Storage::fake('public');

		$this->json( 'POST', route( 'avatar', auth()->id() ), [
			'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
		] );

		$hashName = $file->hashName();
		$this->assertEquals( asset( 'avatars/' . $hashName ), auth()->user()->avatar_path );

		Storage::disk( 'public' )->assertExists( 'avatars/' . $hashName );
	}
}
