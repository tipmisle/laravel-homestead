<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_TITLE') }}</title>
    <link rel="stylesheet" type="text/css" href="/css/app.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.7.0/fullcalendar.min.css">
    @yield('jquery_datetimepicker_style')
</head>
<body>

    <nav class="navbar navbar-default">
        <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Simple Google Calendar Login</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                @if(Auth::check())
                <li class="active"><a id="createCalendar" href="#">Create calendar <span class="sr-only">(current)</span></a></li>
                <li><a href="/calendar/sync">Sync calendar</a></li>
                @endif
            </ul>
            
            <div class="nav navbar-nav navbar-right">
                @if(Auth::check())
                    <li><a>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</a></li>
                    <li><a class="btn btn-primary" href="/logout">Logout</a></li>
                @else
                    <li><a class="btn btn-primary" href="/login">Login with Google</a></li>
                @endif

            </div>
            
        </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    <div class="container">
        @yield('content')
    </div>
    <script type="text/javascript" src="/js/app.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.4/moment.js"></script>    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.7.0/gcal.js"></script>
    @yield('gcalscript')
    
</body>
</html>