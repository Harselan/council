@extends('layouts.app')

@section('content')
	@include('breadcrumbs')

	<div class="pt-6">
		@include ('threads._list')

		{{ $threads->links('vendor.pagination.default') }}
	</div>
@endsection