<div class="form-group">
	<label for="name" >Name:</label >
	<input type="text" class="form-control" name="name" value="{{ isset( $channel ) ? old( 'name', $channel->name ) : '' }}" placeholder="Name:">
</div>

<div class="form-group">
	<label for="description" >Description:</label >
	<input type="text" class="form-control" name="description" value="{{ isset( $channel ) ? old( 'description', $channel->description ) : '' }}" placeholder="Description:">
</div>

<div class="form-group">
	<label for="archived" >Status:</label >
	<select name="archived" class="form-control" >
		<option value="0" {{ isset( $channel ) && old( 'archived', $channel->archived ) ? '' : 'selected' }}>Active</option >
		<option value="1" {{ isset( $channel ) && old( 'archived', $channel->archived ) ? 'selected' : '' }}>Archived</option >
	</select >
</div>

<div class="form-group">
	<button type="submit" class="btn btn-primary btn-sm">{{ $buttonText ?? 'Add Channel' }}</button >
</div>