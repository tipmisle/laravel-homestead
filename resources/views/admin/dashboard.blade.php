@extends('layouts.admin')

@section('content')
<ul>
    <li><a href="/calendar/create">Create Calendar</a></li>
    <li><a href="/event/create">Create Event</a></li>
    <li><a href="/calendar/sync">Sync Calendar</a></li>
    <li><a href="/events">Events</a></li>
    <li><a href="/logout">Logout</a></li>
</ul>
<div id='calendar'></div>

<p>Currently signed in as:</p>
{!! $first,' ', $last !!}

@stop


@section('gcalscript')
<script type="text/javascript">
    $(document).ready(function() {

    // page is now ready, initialize the calendar...

    $('#calendar').fullCalendar({
        googleCalendarApiKey: 'AIzaSyDHq7R6I33swEFtfZq8pz71jhydrj8iGBo',
        eventSources: [
        	@if(!empty($calendars))
        	@foreach ($calendars as $cal)
				@foreach($cal['items'] as $calendar)
					{
                		googleCalendarId: '{{ $calendar['id'] }}'
            		},
			    @endforeach
			@endforeach
			@endif
        ]
    })

});
</script>
@stop