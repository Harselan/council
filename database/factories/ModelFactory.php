<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
	    'username' => $faker->unique()->userName,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
	    'confirmed' => true,
    ];
});

$factory->state( App\User::class, 'unconfirmed', function()
{
	return [
		'confirmed' => false,
	];
} );

$factory->state( App\User::class, 'administrator', function()
{
	return [
		'name' => 'JohnDoe',
	];
} );

$factory->define(App\Thread::class, function( Faker $faker )
{
	$title = $faker->sentence;

	return [
		'user_id' => function()
		{
			return factory( App\User::class )->create()->id;
		},
		'channel_id' => function()
		{
			return factory( App\Channel::class )->create()->id;
		},
		'title' => $title,
		'body' => $faker->paragraph,
		'visits' => 0,
		'slug' => str_slug( $title ),
		'locked' => false
	];
});

$factory->state( App\Thread::class, 'from_existing_channels_and_users', function( Faker $faker )
{
	$title = $faker->sentence;

	return [
		'user_id' => function()
		{
			return \App\User::all()->random()->id;
		},
		'channel_id' => function()
		{
			return \App\Channel::all()->random()->id;
		},
		'title' => $title,
		'body' => $faker->paragraph,
		'visits' => $faker->numberBetween( 0, 35 ),
		'slug' => str_slug( $title ),
		'locked' => $faker->boolean( 15 )
	];
} );

$factory->define(App\Channel::class, function( Faker $faker )
{
	$name = $faker->word;

	return [
		'name' => $name,
		'slug' => $name,
		'description' => $faker->sentence,
		'archived' => false,
		'color' => $faker->hexcolor
	];
});

$factory->define(App\Reply::class, function( Faker $faker )
{
	return [
		'user_id' => function()
		{
			return factory( App\User::class )->create()->id;
		},
		'thread_id' => function()
		{
			return factory( App\Thread::class )->create()->id;
		},
		'body' => $faker->paragraph
	];
});

$factory->define(\Illuminate\Notifications\DatabaseNotification::class, function( Faker $faker )
{
	return [
		'id' => Ramsey\Uuid\Uuid::uuid4()->toString(),
		'type' => 'App\Notifications\ThreadWasUpdated',
		'notifiable_id' => function()
		{
			return auth()->id() ?: factory( 'App\User' )->create()->id;
		},
		'notifiable_type' => 'App\User',
		'data' => [ 'foo' => 'bar' ]
	];
});