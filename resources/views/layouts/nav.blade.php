<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
	<div class="container">
		<a class="navbar-brand" href="{{ url('/') }}">
			{{ config('app.name', 'Laravel') }}
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<!-- Left Side Of Navbar -->
			<ul class="navbar-nav mr-auto">
				<li class="dropdown nav-item">
					<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" >
						Browse <span class="caret"></span>
					</a >
					<ul class="dropdown-menu">
						<li><a class="dropdown-item" href="{{ route( 'threads.index' ) }}" >All Threads</a ></li>
						@if( auth()->check() )
							<li><a class="dropdown-item" href="{{ route( 'threads.index', [ 'by' => auth()->user()->name ] ) }}" >My Threads</a ></li>
						@endif
						<li><a class="dropdown-item" href="{{ route( 'threads.index', [ 'popular' => 1 ] ) }}" >Popular Threads</a ></li>
						<li><a class="dropdown-item" href="{{ route( 'threads.index', [ 'unanswered' => 1 ] ) }}" >Unanswered Threads</a ></li>
					</ul>
				</li>
				<li class="nav-item"><a href="{{ route( 'threads.create' ) }}" class="nav-link" >New Thread</a ></li>
				<li class="dropdown nav-item">
					<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" >
						Channels <span class="caret"></span>
					</a >
					<ul class="dropdown-menu">
						@foreach( $channels as $channel )
							<li><a class="dropdown-item" href="{{ route( 'channels.show', $channel->slug ) }}" >{{ $channel->name }}</a ></li>
						@endforeach
					</ul>
				</li>
			</ul>

			<!-- Right Side Of Navbar -->
			<ul class="navbar-nav ml-auto">
				<!-- Authentication Links -->
				@guest
					<li class="nav-item">
						<a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
					</li>
					@if (Route::has('register'))
						<li class="nav-item">
							<a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
						</li>
					@endif
				@else
					@if( auth()->user()->isAdmin() )
						<li class="nav-item">
							<a href="{{ route( 'admin.dashboard.index' ) }}" class="nav-link" >
								<img src="{{ asset( 'images/settings.png' ) }}" width="20px" alt="" >
							</a >
						</li>
					@endif

					<user-notifications></user-notifications>
					<li class="nav-item dropdown">
						<a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
							{{ Auth::user()->name }} <span class="caret"></span>
						</a>

						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="{{ route( 'profile', auth()->user() ) }}" >Profile</a >
							<a class="dropdown-item" href="{{ route('logout') }}"
							   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
								{{ __('Logout') }}
							</a>

							<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
								@csrf
							</form>
						</div>
					</li>
				@endguest
			</ul>
		</div>
	</div>
</nav>