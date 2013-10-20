<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

    {{-- <link rel="stylesheet" type="text/css" href="//normalize-css.googlecode.com/svn/trunk/normalize.css"/> --}}


    <link type="text/css" href="//netdna.bootstrapcdn.com/bootswatch/3.0.0/flatly/bootstrap.min.css" rel="stylesheet">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="/assets/style.css"/>

    {{-- This is a Laravel Blade comment, text between these parts will be left out of the HTML output! --}}
    {{-- Yield the title from the view or if it isn't set, use the default title given at install --}}
    <title>@yield('title', Config::get('settings.appSettings.defaultTitle'))</title>

    <script>
        $(document).ready( function()
        {
            var serverTime = <?php echo time() * 1000 ?>;
            setTime();
            function setTime()
            {
                    var date = new Date(serverTime);
                    var datestr = '';
                    datestr = ('0' + date.getHours()).slice(-2) + ':' + ('0' + date.getMinutes()).slice(-2) + ':' + ('0' + date.getSeconds()).slice(-2) +
                        ' / ' + ('0' + date.getDate()).slice(-2) + '-' + ('0' + (date.getMonth()+1)).slice(-2) + '-' + date.getFullYear();
                    $('#serverTime').text(datestr);
                    serverTime += 1000;
                    setTimeout(setTime, 1000)
            }

        });
    </script>


        @yield('script')

</head>
<body>

    {{-- header div --}}
    <div class="row header-container">

                <a href="/" class="col-md-4 col-md-offset-1 header-title">{{ Config::get('settings.appSettings.forumName') }}</a>

    </div>
    <div class="row user-cp-container">

                <div class="col-md-10 col-md-offset-1">
                    <div class="pull-left">
                    @if(Auth::guest())
                        Hi guest
                    @else
                        Hi {{{ Auth::user()->username }}}
                    @endif
                    </div>
                    <div class="text-right pull-right">
                        Server time:

                        <span id="serverTime" class="small-text"></span>
                    </div>
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