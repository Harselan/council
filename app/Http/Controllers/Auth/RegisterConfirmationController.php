<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
	    $user = User::where( 'confirmation_token', request( 'token' ) )
		    ->first();

	    if( !$user )
	    {
	    	return redirect()->route( 'threads.index' )->with( 'flash', 'Unknown token.' );
	    }

	    $user->confirm();

    	return redirect()->route( 'threads.index' )
		    ->with( 'flash', 'Your account is now confirmed! You may post to the forum.' );
    }
}
