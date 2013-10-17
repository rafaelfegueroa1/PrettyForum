<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

    <link rel="stylesheet" type="text/css" href="//normalize-css.googlecode.com/svn/trunk/normalize.css"/>


    <link type="text/css" href="//netdna.bootstrapcdn.com/bootswatch/3.0.0/flatly/bootstrap.min.css" rel="stylesheet">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="/assets/style.css"/>

    {{-- This is a Laravel Blade comment, text between these parts will be left out of the HTML output! --}}
    {{-- Yield the title from the view or if it isn't set, use the default title given at install --}}
    <title>@yield('title', Config::get('settings.appSettings.defaultTitle'))</title>
</head>
<body>

    {{-- header div --}}
    <div class="row header-container">

                <div class="col-md-4 col-md-offset-1 header-title">{{ Config::get('settings.appSettings.forumName') }}</div>

    </div>
    <div class="row user-cp-container">

                <div class="col-md-10 col-md-offset-1">
                    @if(Auth::guest())
                        Hi guest
                    @else
                        Hi {{{ Auth::user()->username }}}
                    @endif
                </div>

    </div>

    {{-- content div --}}
    <div class="row content">
        <div class="col-md-10 col-md-offset-1">
            @yield('content')

        </div>
    </div>


    {{-- footer div --}}
    <div class="row footer">
        <div class="col-md-10 col-md-offset-1">
            Footer
        </div>
    </div>
</body>
</html>