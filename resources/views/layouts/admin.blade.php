<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ env('APP_TITLE') }}</title>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.7.0/fullcalendar.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.7.0/fullcalendar.print.css">
    @yield('jquery_datetimepicker_style')
</head>
<body>
    <div class="container">
        <header>
            <h1>{{ env('APP_TITLE') }}</h1>
        </header>
        @yield('content')
    </div>
    <script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>        
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.3/moment.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.7.0/fullcalendar.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.7.0/gcal.js"></script>
    @yield('gcalscript')
    
</body>
</html>