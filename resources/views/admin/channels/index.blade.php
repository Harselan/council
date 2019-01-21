@extends('admin.layouts.app')

@section('content')
	<div class="card">
		<div class="card-header bg-white">
			<a href="{{ route( 'admin.channels.create' ) }}" class="btn btn-outline-success btn-sm" >Create Channel</a >
		</div>

		<div class="card-body">
			<table class="table">
				<tr>
					<td>Name</td>
					<td>Slug</td>
					<td>Description</td>
					<td>Threads</td>
				</tr>

				@foreach( $channels as $channel )
					<tr>
						<td>{{ $channel->name }}</td>
						<td>{{ $channel->slug }}</td>
						<td>{{ $channel->description }}</td>
						<td>{{ $channel->threads_count }}</td>
					</tr>
				@endforeach
			</table>
		</div>
	</div>
@endsection