<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reputation extends Model
{
    public static function gain( $user, $points )
    {
    	$user->increment( 'reputation', $points );
    }

    public static function lose( $user, $points )
    {
    	$user->decrement( 'reputation', $points );
    }
}
