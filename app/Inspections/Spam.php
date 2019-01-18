<?php
namespace App\Inspections;

class Spam
{
	protected static $inspections = [
		InvalidKeywords::class,
		KeyHeldDown::class
	];

	protected static $spamKeys   = [ 'body' ];
	protected static $spamEvents = [ 'saving', 'created', 'updated' ];

	protected static function bootSpamFilter()
	{
		foreach( static::$spamEvents as $event )
		{
			static::$event( function( $model )
			{
				foreach( static::$spamKeys as $key )
				{
					static::detect( $model->$key );
				}
			} );
		}
	}

	public static function detect( $body )
	{
		foreach( static::$inspections as $inspection )
		{
			app( $inspection )->detect( $body );
		}

		return false;
	}
}