@extends('admin.layouts.app')

@section('content')
	<div class="card">
		<div class="card-body">
			<form action="{{ route( 'admin.channels.store' ) }}" method="post">
				@csrf
				<div class="form-group">
					<label for="name" >Name:</label >
					<input type="text" class="form-control" name="name" placeholder="Name:">
				</div>

				<div class="form-group">
					<label for="description" >Description:</label >
					<input type="text" class="form-control" name="description" placeholder="Description:">
				</div>

				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-sm">Add</button >
				</div>
			</form >
		</div>
	</div>
@endsection