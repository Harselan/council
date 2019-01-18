<?php
namespace App\Inspections;

trait SpamFilter
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

	protected static function detect( $body )
	{
		foreach( static::$inspections as $inspection )
		{
			app( $inspection )->detect( $body );
		}

		return false;
	}
}