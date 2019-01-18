<reply :attributes="{{ $reply }}" inline-template v-cloak>
	<div id="reply-{{ $reply->id }}" class="card mt-2">
		<div class="card-header bg-white">
			<div class="level">
				<h5 class="flex"><a href="{{ route( 'profile', $reply->owner ) }}" >{{ $reply->owner->name }}</a > said {{ $reply->created_at->diffForHumans() }}...</h5>

				@if( Auth::check() )
				<div>
					<favorite :reply="{{ $reply }}"></favorite>
				</div>
				@endif
			</div>
		</div>
		<div class="card-body">
			<div v-if="editing">
				<div class="form-group">
					<textarea class="form-control" v-model="body"></textarea >
				</div>

				<button class="btn btn-primary btn-sm" @click="update">Update</button >
				<button class="btn btn-link btn-sm" @click="editing = false">Cancel</button >
			</div>
			<div v-else v-text="body"></div>
		</div>
		@can( 'update', $reply )
			<div class="card-footer bg-white level">
				<button class="btn btn-warning btn-sm mr-2" @click="editing = true" >Edit</button >
				<button class="btn btn-danger btn-sm" @click="destroy" >Delete</button >
			</div>
		@endcan
	</div>
</reply>