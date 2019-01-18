<?php

namespace App;

trait Favoritable
{
	protected static function bootFavoritable()
	{
		static::deleting( function( $model )
		{
			$model->favorites->each->delete();
		} );
	}

	public function favorites()
	{
		return $this->morphMany( Favorite::class, 'favorited' );
	}

	public function toggleFavorite()
	{
		if( $this->isFavorited() )
		{
			$this->unfavorite();
		}
		else
		{
			$this->favorite();
		}
	}

	public function favorite()
	{
		$attributes = [ 'user_id' => auth()->id() ];

		if( !$this->favorites()->where( $attributes )->exists() )
		{
			Reputation::award( auth()->user(), Reputation::REPLY_FAVORITED );

			$this->favorites()->create( $attributes );
		};
	}

	public function unfavorite()
	{
		$attributes = [ 'user_id' => auth()->id() ];

		Reputation::reduce( auth()->user(), Reputation::REPLY_FAVORITED );

		$this->favorites()->where( $attributes )->get()->each->delete();
	}

	public function isFavorited()
	{
		return !!$this->favorites->where( 'user_id', auth()->id() )->count();
	}

	public function getIsFavoritedAttribute()
	{
		return $this->isFavorited();
	}
}