<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [ 'user_id', 'type', 'subject_id', 'subject_type', 'created_at' ];
    protected $appends = [ 'favoritedModel' ];

    public function getFavoritedModelAttribute()
    {
    	$favoritedModel = null;

    	if( $this->subject_type === Favorite::class )
	    {
	    	$subject = $this->subject()->firstOrFail();

	    	if( $subject->favorited_type === Reply::class )
		    {
		    	$favoritedModel = Reply::find( $subject->favorited_id );
		    }
	    }

	    return $favoritedModel;
    }

    public function subject()
    {
    	return $this->morphTo();
    }

    public static function feed( $user )
    {
	    return static::where( 'user_id', $user->id )
		    ->latest()
		    ->with( 'subject' )
		    ->paginate( 30 );
    }
}
