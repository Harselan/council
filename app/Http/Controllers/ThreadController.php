<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Channel;
use App\Trending;
use App\Rules\Recaptcha;
use App\Filters\ThreadFilters;

class ThreadController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth')->except([ 'index', 'show' ]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @param Channel $channel
	 * @param ThreadFilters $filters
	 * @param Trending $trending
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
    public function index( Channel $channel, ThreadFilters $filters, Trending $trending )
    {
	    $threads = $this->getThreads( $channel, $filters );

	    if( request()->wantsJson() )
	    {
	    	return $threads;
	    }

        return view( 'threads.index', [
	        'threads'   => $threads,
	        'trending'  => $trending->get()
        ] );
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
    public function create()
    {
        return view( 'threads.create', [ 'channels' => Channel::all() ] );
    }

	/**
	 * @return \Illuminate\Http\RedirectResponse
	 */
    public function store( Recaptcha $recaptcha )
    {
    	request()->validate( [
    		'title'         => 'required|spamfree',
		    'body'          => 'required|spamfree',
		    'channel_id'    => 'required|exists:channels,id',
		    'g-recaptcha-response' => [ 'required', $recaptcha ]
	    ] );

        $thread = Thread::create( [
        	'title'         => request( 'title' ),
	        'body'          => request( 'body' ),
	        'channel_id'    => request( 'channel_id' ),
	        'user_id'       => auth()->id(),
        ] );

        if( request()->wantsJson() )
        {
        	return response( $thread, 201 );
        }

        return redirect( route( 'threads.show', [ $thread->channel->slug, $thread->slug ] ) )
	        ->with( 'flash', 'Your thread has been published' );
    }

	/**
	 * Display the specified resource.
	 *
	 * @param Integer $channelId
	 * @param Thread $thread
	 * @param Trending $trending
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
    public function show( $channelId, Thread $thread, Trending $trending )
    {
    	if( auth()->check() )
	    {
		    auth()->user()->read( $thread );
	    }

	    $trending->push( $thread );

    	$thread->increment('visits');

        return view( 'threads.show', [
        	'thread' => $thread,
        ] );
    }

    public function update( $channelId, Thread $thread )
    {
	    $this->authorize( 'update', $thread );

	    $thread->update( request()->validate( [
		    'title' => 'required|spamfree',
		    'body'  => 'required|spamfree',
	    ] ) );

	    return $thread;
    }

	/**
	 * @param Integer $channelId
	 * @param Thread $thread
	 *
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector|\Symfony\Component\HttpFoundation\Response
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
    public function destroy( $channelId, Thread $thread )
    {
    	$this->authorize( 'update', $thread );

    	$thread->delete();

    	if( request()->wantsJson() )
	    {
		    return response( [], 204 );
	    }

	    return redirect( route( 'threads.index' ) );
    }

	/**
	 * @param Channel $channel
	 * @param ThreadFilters $filters
	 *
	 * @return mixed
	 */
	public function getThreads( Channel $channel, ThreadFilters $filters )
	{
		$threads = Thread::orderBy( 'pinned', 'DESC' )
			->latest()
			->filter( $filters );

		if( $channel->exists )
		{
			$threads->where( 'channel_id', $channel->id );
		}

		return $threads->paginate( 25 );
	}
}
