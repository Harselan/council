<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <script >
        window.App = {!! json_encode( [
            'csrfToken' => csrf_token(),
            'user'      => Auth::user(),
            'signedIn'  => Auth::check(),
         ] ) !!};
    </script >

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.0.0/trix.css" rel="stylesheet" >
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.js"></script>


    <style >
        body { padding-bottom:100px; }
        .level { display:flex; align-items: center; }
        .flex { flex: 1; }
        [v-cloak] { display:none; }
        .ais-highlight > em { background: yellow; font-style: normal; }
    </style >

    @yield( 'head' )
</head>
<body style="padding-bottom:100px;">
    <div id="app">
        @include( 'layouts.nav' )

        @if( auth()->check() && auth()->user()->isAdmin() )
            <div class="row mt-4 mx-2">
                <div class="col-md-3">
                    @include( 'admin.layouts.nav' )
                </div>
                <div class="col-md">
                    @yield('content')
                </div>
            </div>
        @else
            @yield('content')
        @endif
        <flash message="{{ session( 'flash' ) }}"></flash>
    </div>
    @yield( 'scripts' )
</body>
</html>
