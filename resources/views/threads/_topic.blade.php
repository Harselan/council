{{-- Editing the thread --}}
<div class="card card-default" v-if="editing" v-cloak>
	<div class="card-header bg-white">
		<div class="level">
			<input class="form-control" type="text" v-model="form.title">
		</div>
	</div>

	<div class="card-body">
		<div class="form-group">
			<wysiwyg v-model="form.body"></wysiwyg>
		</div>
	</div>

	<div class="card-footer bg-white">
		<div class="level">
			<button class="btn btn-sm btn-outline-primary" @click="update">Update</button>
			<button class="btn btn-sm btn-outline-info ml-2" @click="resetForm">Cancel</button>
			<form method="post" class="ml-auto" action="{{ route( 'threads.destroy', [ $thread->channel->slug, $thread->id ] ) }}" >
				@csrf
				@method('DELETE')

				<button type="submit" class="btn btn-outline-danger btn-sm" >Delete thread</button >
			</form >
		</div>
	</div>
</div>

{{-- Viewing the thread --}}
<div class="card card-default" v-else>
	<div class="card-header bg-white">
		<div class="level">

			<img src="{{ asset( $thread->creator->avatar_path ) }}" alt="{{ $thread->creator->name }}" class="mr-2" width="25" height="25">

			<span class="flex">
				<a href="{{ route( 'profile', $thread->creator ) }}" >
					{{ $thread->creator->name }}
				</a >

				posted: <span v-text="title"></span>
			</span>
		</div>
	</div>

	<div class="card-body" v-html="body"></div>

	<div class="card-footer bg-white" v-if="authorize( 'owns', thread )">
		<button class="btn btn-sm btn-outline-info" @click="editing = true">Edit</button>
	</div>
</div>