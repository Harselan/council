<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use App\Channel;
use App\Http\Requests\CreatePostRequest;

class ReplyController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth', [ 'except' => 'index' ]);
	}

	/**
	 * @param Channel $channel
	 * @param Thread $thread
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function index( Channel $channel, Thread $thread )
	{
		return $thread->replies()->paginate(20);
	}

	/**
	 * @param $channelId
	 * @param Thread $thread
	 * @param CreatePostRequest $form
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 */
    public function store( $channelId, Thread $thread, CreatePostRequest $form )
    {
    	if( $thread->locked )
	    {
	    	return response( "Thread is locked", 422 );
	    }

	    return $thread->addReply( [
		    'user_id'   => auth()->id(),
		    'body'      => request( 'body' )
	    ] )->load('owner');
    }

	/**
	 * Update an existing reply
	 * @param Reply $reply
	 *
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
    public function update( Reply $reply )
    {
	    $this->authorize( 'update', $reply );

	    request()->validate( [ 'body' => 'required|spamfree' ] );

		$reply->update( request(['body']) );
    }

	/**
	 * @param Reply $reply
	 *
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
    public function destroy( Reply $reply )
    {
    	$this->authorize( 'update', $reply );

	    $reply->delete();

	    if( request()->expectsJson() )
	    {
	    	return response( [ 'status' => 'Reply deleted' ] );
	    }

	    return back();
    }
}
