@extends('admin.layouts.app')

@section('content')
	<div class="card">
		<div class="card-body">
			<form action="{{ route( 'admin.channels.update', $channel ) }}" method="post">
				@csrf
				@method('PATCH')
				@include( 'admin.channels._form', [ 'buttonText' => 'Update Channel' ] )
			</form >
		</div>
	</div>
@endsection