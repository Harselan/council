@extends('layouts.app')

@section('head')
	<script src='https://www.google.com/recaptcha/api.js?render={{ config( 'services.recaptcha.public' ) }}'></script>
	<script >
        var recaptchaKey = '{{ config( 'services.recaptcha.public' ) }}';
        $(document).ready( function()
        {
            // when form is submit
            $( 'form' ).submit( function()
            {
                event.preventDefault();
                var form = $('form');

                grecaptcha.ready(function()
                {
                    grecaptcha.execute( recaptchaKey, { action: 'create_thread' } ).then( function( token )
                    {
                        form.prepend('<input type="hidden" name="g-recaptcha-response" value="' + token + '">');
                        form.prepend('<input type="hidden" name="action" value="create_thread">');

                        form.unbind('submit').submit();
                    } );
                } );
            } );
        } );
	</script >
@endsection

@section('content')
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">Create a New Thread</div>

					<div class="card-body">
						<form action="{{ route( 'threads.store' ) }}" id="recaptcha_form" method="POST" >
							@csrf
							<div class="form-group">
								<label for="channel_id" >Choose a Channel:</label >
								<select class="form-control" name="channel_id" id="channel_id" selected >
									<option value="" >Choose One...</option >
									@foreach( $channels as $channel )
										<option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : '' }}>{{ $channel->name }}</option >
									@endforeach
								</select >
							</div>

							<div class="form-group">
								<label for="title" >Title:</label >
								<input type="text" name="title" class="form-control" id="title" value="{{ old('title') }}" required >
							</div>

							<div class="form-group">
								<label for="body" >Body:</label >
								<wysiwyg name="body"></wysiwyg>
							</div>

							<div class="form-group">
								<button type="submit" class="btn btn-primary">Publish</button>
							</div>

							@if( count( $errors ) )
								<ul class="alert alert-danger">
									@foreach( $errors->all() as $error )
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							@endif
						</form >
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection