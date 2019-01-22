<?php

namespace App\Http\Controllers\Admin;

use App\Channel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class ChannelController extends Controller
{
	public function index()
	{
		return view( 'Admin.channels.index', [ 'channels' => Channel::withoutGlobalScopes()->withCount('threads')->get() ] );
	}

	public function create()
	{
		return view( 'Admin.channels.create' );
	}

	public function store()
	{
		$data = request()->validate([
			'name' => 'required|unique:channels',
			'description' => 'required',
		]);
		$channel = Channel::create($data + [ 'slug' => str_slug($data['name'])]);
		Cache::forget('channels');
		if (request()->wantsJson()) {
			return response($channel, 201);
		}
		return redirect(route('admin.channels.index'))
			->with('flash', 'Your channel has been created!');
	}

	public function edit( Channel $channel )
	{
		return view( 'admin.channels.edit', [ 'channel' => $channel ] );
	}

	public function update( Channel $channel )
	{
		$channel->update( request()->validate( [
				'name' => [ 'required', Rule::unique( 'channels', 'slug' )->ignore( $channel->id ) ],
				'description' => 'required',
				'archived' => 'required|boolean',
			] ) + [ 'slug' => str_slug( request('name') ) ] );

		Cache::forget('channels');

		if( request()->wantsJson() )
		{
			return response( $channel, 201 );
		}

		return redirect( route( 'admin.channels.index' ) )
			->with( 'flash', 'Your channel has been updated!' );
	}
}
