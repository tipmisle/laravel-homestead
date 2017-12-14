@extends('layouts.default')

@section('content')
<h1>Koledar osebe: <u>{{ $user->first_name }} {{ $user->last_name }}</u></h1>
<div id='calendar'></div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create new event</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="a">
          <div class="modal-body">
              <div class="form-group">
                <label for="date">Date</label>
                <p name="date" id="date"></p>
              </div>
              <div class="form-group">
                <select class="form-control" name="calendar_id" id="calendar_id">
                    @if(!empty($mycalendars))
                        @foreach($mycalendars as $cal)
                                <option value="{{ $cal->calendar_id }}">{{ $cal->title }}</option>
                        @endforeach
                    @endif
                </select>
              </div>
              <div class="form-group">
                <label for="time_s">Start time</label>
                <input name="time_s" type="time" class="form-control" id="time_s" value="15:30">
              </div>
              <div class="form-group">
                <label for="time_e">End time</label>
                <input name="time_e" type="time" class="form-control" id="time_e" value="16:30">
              </div>              
              <div class="form-group">
                <label for="summary">Summary</label>
                <input name="summary" type="text" class="form-control" id="summary" placeholder="Summary">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <p id="user_id" name="user_id" hidden>{{ $user->id }}</p>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
    </div>
  </div>
</div>
@stop

@section('gcalscript')
<script type="text/javascript">
    $(document).ready(function() {

    // page is now ready, initialize the calendar...

    $('#calendar').fullCalendar({
        googleCalendarApiKey: '{{ env('GOOGLE_API_KEY') }}',
        eventSources: [
            @if(!empty($calendars))
                @foreach ($calendars as $cal)
                        {
                            googleCalendarId: '{{ $cal->calendar_id }}'
                        },
                @endforeach
            @endif
        ],
        
        eventRender: function (event, element) {
            @if($user->id != Auth::user()->id)
                element.html('<p><b>Occupied!</b></p>');
            @endif
        },
        eventClick: function(event) {
            if (event.url) {
                return false;
            }
        },
        defaultView: 'month',
        dayRender: function(date, cell) {
            var today = moment();      
        },
        dayClick: function(date, allDay, jsEvent, view) {
            var today = moment();
            if (date >= today) {
                $('#exampleModal').modal('show');
                var selectedDate = date.format('DD.MM.YYYY');
                $('#a #date').text(selectedDate);
            } else {
                alert("Can't select date in the past!")
            }
        }
    });
});
</script>
@stop