<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [ 'user_id', 'type', 'subject_id', 'subject_type', 'created_at' ];

    public function subject()
    {
    	return $this->morphTo();
    }

    public static function feed( $user, $take = 50 )
    {
	    return $user->activity()
		    ->latest()
		    ->with( 'subject' )
		    ->take( $take )
		    ->get()
		    ->groupBy( function ( $acticity )
		    {
			    return $acticity->created_at->format( 'Y-m-d' );
		    } );
    }
}
