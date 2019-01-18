@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				@include( 'threads._list' )

				{{ $threads->render() }}
			</div>
			@if( count($trending) )
				<div class="col-md-4">
					<div class="card mt-3">
						<div class="card-header bg-white">
							Search
						</div>
						<div class="card-body">
							<form method="GET" action="{{ route( 'threads.search' ) }}" >
								<div class="form-group">
									<input type="text" class="form-control" name="q" placeholder="Search for something...">
								</div>
								<div class="form-group">
									<button class="btn btn-outline-primary btn-block" type="submit">Search</button >
								</div>
							</form >
						</div>
					</div>

					<div class="card mt-3">
						<div class="card-header bg-white">
							Trending Threads
						</div>
						<div class="card-body">
							<ul class="list-group">
								@foreach( $trending as $thread )
									<li class="list-group-item">
										<a href="{{ url($thread->path) }}" >{{ $thread->title }}</a >
									</li>
								@endforeach
							</ul>
						</div>
					</div>
				</div>
			@endif
		</div>
	</div>
@endsection
