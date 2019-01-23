@extends('layouts.app')

@section('content')
	@include('breadcrumbs')

	<search start-search="{{ request( 'q' ) ?? '' }}"></search>
@endsection