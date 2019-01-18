<?php

namespace App\Http\Controllers;

use App\Reply;
use DB;

class FavoriteController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function store( Reply $reply )
	{
		$reply->toggleFavorite();

		return back();
	}
}
