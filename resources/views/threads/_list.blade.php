@forelse( $threads as $thread )
	<div class="card mt-3">
		<div class="card-header bg-white">
			<div class="level">
				<div class="flex">
					<h5 class="text-sm">
						<a href="{{ $thread->path() }}">
							@if( $thread->pinned )
								<i class="fa fa-map-pin"></i>
							@endif
							@if( Auth::check() && $thread->hasUpdatesFor( auth()->user() ) )
								<strong>{{ $thread->title }}</strong>
							@else
								{{ $thread->title }}
							@endif
						</a>
					</h5>

					<h6>Posted By: <a href="{{ route( 'profile', $thread->creator ) }}">{{ $thread->creator->name }}</a></h6>
				</div>

				<a href="{{ $thread->path() }}">{{ $thread->replies_count }} {{ str_plural( 'reply', $thread->replies_count ) }}</a>
			</div>
		</div>

		<div class="card-body">
			<div class="body">
				{!! $thread->body !!}
			</div>
		</div>
		<div class="card-footer">
			{{ $thread->visits }} Visits
		</div>
	</div>
@empty
	<p>There are no relevant results at this time</p>
@endforelse