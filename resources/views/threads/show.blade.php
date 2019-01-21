@extends('layouts.app')

@section('head')
	<link rel="stylesheet" href="{{ asset('css/vendor.css') }}" >
@endsection

@section('content')
	<thread-view :thread="{{ $thread }}" inline-template>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-8">
					@include('threads._topic')

					<replies @added="repliesCount++" @removed="repliesCount--"></replies>
				</div>

				<div class="col-md-4">
					<div class="card">
						<div class="card-body">
							<p>
								This thread was published {{ $thread->created_at->diffForHumans() }} by <a href="#" >{{ $thread->creator->name }}</a >,
								and currently has <span v-text="repliesCount"></span> {{ str_plural( 'comment', $thread->replies_count )  }}.
							</p>
							<p>
								<subscribe-button :active="{{ json_encode( $thread->isSubscribedTo ) }}" v-if="signedIn"></subscribe-button>

								<button :class="classes(locked)"
								        v-if="authorize( 'isAdmin' )"
								        @click="toggleLock"
								        v-text="locked ? 'Unlock' : 'Lock'">
								</button>

								<button :class="classes(pinned)"
								        v-if="authorize( 'isAdmin' )"
								        @click="togglePin"
								        v-text="pinned ? 'Un-Pin' : 'Pin'">
								</button>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</thread-view>
@endsection
