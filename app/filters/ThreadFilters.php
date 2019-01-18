<?php

namespace App\Filters;

use App\User;

class ThreadFilters extends Filter
{
	protected $filters = [ 'by', 'popular', 'unanswered' ];

	protected function by( $userName )
	{
		$user = User::where( 'name', $userName )->firstOrFail();

		return $this->builder->where( 'user_id', $user->id );
	}

	/**
	 * Filter the query according to most popular threads
	 * @return $this
	 */
	protected function popular()
	{
		$this->builder->getQuery()->orders = [];
		return $this->builder->orderBy( 'replies_count', 'desc' );
	}

	protected function unanswered()
	{
		return $this->builder->where( 'replies_count', 0 );
	}
}