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
					<td>Status</td>
					<td>Actions</td>
				</tr>

				@forelse( $channels as $channel )
					<tr>
						<td>{{ $channel->name }}</td>
						<td>{{ $channel->slug }}</td>
						<td>{{ $channel->description }}</td>
						<td>{{ $channel->archived ? 'archived' : 'active' }}</td>
						<td>x</td>
						<td><a class="btn btn-outline-primary btn-sm" href="{{ route( 'admin.channels.edit', $channel ) }}" >Edit</a ></td>
					</tr>
				@empty
					<tr>
						<td>Nothing here.</td>
					</tr>
				@endforelse
			</table>
		</div>
	</div>
@endsection