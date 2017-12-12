@extends('layouts.admin')

@section('content')
<p>Currently signed in as:</p>
<ul>
    <li><a href="/calendar/create">Create Calendar</a></li>
    <li><a href="/event/create">Create Event</a></li>
    <li><a href="/calendar/sync">Sync Calendar</a></li>
    <li><a href="/events">Events</a></li>
    <li><a href="/logout">Logout</a></li>
</ul>
<div id='calendar'></div>

@section('gcalscript')
<script type="text/javascript">
    $(document).ready(function() {

    // page is now ready, initialize the calendar...

    $('#calendar').fullCalendar({
        googleCalendarApiKey: 'AIzaSyDHq7R6I33swEFtfZq8pz71jhydrj8iGBo',
        eventSources: [
        	@if(!$calendars->isEmpty())
        	@foreach ($calendars as $cal)
					{
                		googleCalendarId: '{{ $cal->calendar_id }}'
            		},
			@endforeach
			@endif
        ]
    })

});
</script>
@stop