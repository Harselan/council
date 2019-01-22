@extends('admin.layouts.app')

@section('content')
	<div class="card">
		<div class="card-body">
			<form action="{{ route( 'admin.channels.store' ) }}" method="post">
				@csrf
				@include( 'admin.channels._form' )
			</form >
		</div>
	</div>
@endsection