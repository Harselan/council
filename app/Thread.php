<?php

namespace App;

use App\Inspections\SpamFilter;
use App\Events\ThreadReceivedNewReply;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
	use RecordsActivity, SpamFilter;

	protected $fillable = [ 'title', 'body', 'user_id', 'channel_id', 'slug', 'best_reply_id', 'locked', 'pinned' ];
	protected $with     = [ 'creator', 'channel' ];
	protected $casts    = [ 'locked' => 'boolean', 'pinned' => 'boolean' ];
	protected $appends  = [ 'path' ];
	protected static $searchableColumns = [ 'title' ];

	protected static function boot()
	{
		parent::boot();

		static::deleting( function ( $thread )
		{
			$thread->replies->each->delete();

			Reputation::lose( $thread->creator, config('council.reputation.thread_was_published') );
		} );

		static::created( function ( $thread )
		{
			$thread->update( [ 'slug' => $thread->title ] );

			Reputation::gain( $thread->creator, config('council.reputation.thread_was_published') );
		} );
	}

	public function path()
	{
		return "/threads/{$this->channel->slug}/{$this->slug}";
	}

	public function getPathAttribute()
	{
		if( !$this->channel )
		{
			return '';
		}

		return $this->path();
	}

	public function replies()
	{
		return $this->hasMany( Reply::class );
	}

	public function getReplyCountAttribute()
	{
		return $this->replies()->count();
	}

	public function creator()
	{
		return $this->belongsTo( User::class, 'user_id' );
	}

	public function title()
	{
		return $this->title;
	}

	public function channel()
	{
		return $this->belongsTo( Channel::class )->withoutGlobalScope( 'active' );
	}

	public function addReply( $reply )
	{
		$reply = $this->replies()->create( $reply );

		event( new ThreadReceivedNewReply( $reply ) );

		return $reply;
	}

	public function notifySubscribers( $reply )
	{
		$this->subscriptions
			->where( 'user_id', '!=', $reply->user_id )
			->each
			->notify( $reply );
	}

	public function scopeFilter( $query, $filters )
	{
		return $filters->apply( $query );
	}

	public function subscribe( $userId = null )
	{
		$this->subscriptions()->create(
			[
				'user_id' => $userId ? : auth()->id()
			] );

		return $this;
	}

	public function unsubscribe( $userId = null )
	{
		$this->subscriptions()
			->where( 'user_id', $userId ? : auth()->id() )
			->delete();
	}

	public function subscriptions()
	{
		return $this->hasMany( ThreadSubscription::class );
	}

	public function getIsSubscribedToAttribute()
	{
		return $this->subscriptions()
			->where( 'user_id', auth()->id() )
			->exists();
	}

	public function hasUpdatesFor( $user )
	{
		$key = $user->visitedThreadCacheKey( $this );

		return $this->updated_at > cache( $key );
	}

	public function getRouteKeyName()
	{
		return 'slug';
	}

	public function setSlugAttribute( $value )
	{
		$slug = str_slug( $value );

		if( static::whereSlug( $slug )->exists() )
		{
			$slug .= "-" . $this->id;
		}

		$this->attributes['slug'] = $slug;
	}

	public function markBestReply( Reply $reply )
	{
		$this->update( [ 'best_reply_id' => $reply->id ] );

		Reputation::gain( $reply->owner, config('council.reputation.best_reply_awarded') );
	}

	public function toSearchableArray()
	{
		return $this->toArray() + [ 'path' => $this->path() ];
	}

	public static function search( $q )
	{
		$search = self::query();

		foreach( self::$searchableColumns as $column )
		{
			$search->where( $column, 'like', '%' . $q . '%' );
		}

		return $search;
	}

	public function getBodyAttribute( $body )
	{
		return \Purify::clean( $body );
	}
}
