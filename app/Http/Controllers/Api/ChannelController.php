<?php

namespace App\Http\Controllers\Api;

use App\http\Controllers\Controller;
use App\Channel;

class ChannelController extends Controller
{
	public function index()
	{
		return cache()->rememberForever( 'channels', function()
		{
			return Channel::all();
		} );
	}
}