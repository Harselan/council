<?php

namespace App;

use Carbon\Carbon;
use App\Inspections\SpamFilter;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
	use Favoritable, RecordsActivity, SpamFilter;

	protected $fillable = [ 'thread_id', 'user_id', 'body' ];
	protected $appends  = [ 'favoritesCount', 'isFavorited', 'isBest', 'xp', 'path' ];

	protected $with = [ 'owner', 'favorites' ];

	protected static function boot()
	{
		parent::boot();

		static::created( function( $reply )
		{
			$reply->thread->increment('replies_count');
			Reputation::gain( $reply->owner, config('council.reputation.reply_posted') );
		} );

		static::deleted( function( $reply )
		{
			$reply->thread->decrement('replies_count');
			Reputation::lose( $reply->owner, config('council.reputation.reply_posted') );
		} );
	}

	public function owner()
	{
		return $this->belongsTo( User::class, 'user_id' );
	}

	public function thread()
	{
		return $this->belongsTo( Thread::class );
	}

	public function title()
	{
		return $this->thread->title;
	}

	public function wasJustPublished()
	{
		return $this->created_at->gt( Carbon::now()->subMinute() );
	}

	public function mentionedUsers()
	{
		preg_match_all( '/@([\w\-]+)/', $this->body, $matches );

		return $matches[1];
	}

	public function getFavoritesCountAttribute()
	{
		return $this->favorites->count();
	}

	public function path()
	{
		$perPage        = config('council.pagination.perPage');
		$replyPosition  = $this->thread->replies()->pluck('id')->search( $this->id ) + 1;

		$page = ceil( $replyPosition / $perPage );

		return $this->thread->path() . "?page={$page}#reply-{$this->id}";
	}

	public function getPathAttribute()
	{
		return $this->path();
	}

	public function getXpAttribute()
	{
		$xp = config( 'council.reputation.reply_posted' );

		if( $this->isBest() )
		{
			$xp += config( 'council.reputation.best_reply_awarded' );
		}

		return $xp += $this->favorites()->count() * config( 'council.reputation.reply_favorited' );
	}

	public function setBodyAttribute( $body )
	{
		$this->attributes['body'] = preg_replace( '/@([\w\-]+)/', '<a href="/profiles/$1">$0</a>', $body );
	}

	public function isBest()
	{
		return $this->thread->best_reply_id == $this->id;
	}

	public function getIsBestAttribute()
	{
		return $this->thread->best_reply_id == $this->id;
	}

	public function getBodyAttribute( $body )
	{
		return \Purify::clean( $body );
	}
}
