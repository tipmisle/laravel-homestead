@extends('layouts.default')

@section('content')
<div class="row" style="margin-bottom: 20px; padding-bottom: 5px; border-bottom: 1px solid rgba(0, 0, 0, 0.1);">
  <div class="col-lg-8 col-md-8 col-sm-8" style="margin-bottom:0;">
    <h1 style="margin-top:0;">Moj koledar</h1>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-4" class="form-group">
    <p>Share link:</p>
    <input type="text" name="url" value="http://simplecalendarlogin.com/profile/{{Auth::user()->id}}" class="form-control">
  </div>
</div>
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
      <form id="f">
          <div class="modal-body">
              <div class="alert alert-success" style="display: none;">
                <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>Event is being created. Wait a moment!
              </div>
              <div class="form-group">
                <label for="date">Date</label>
                <p name="date" id="date"></p>
              </div>
              <div class="form-group">
                <select class="form-control" name="calendar_id" id="calendar_id">
                    @if(!empty($calendars))
                    @foreach($calendars as $cal)
                        @foreach($cal['items'] as $calendar)
                            <option value="{{ $calendar['id'] }}">{{ $calendar['summary'] }}</option>
                        @endforeach
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
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
    </div>
  </div>
</div>
<div class="modal fade" id="createCalendarModal" tabindex="-1" role="dialog" aria-labelledby="createCalendarModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create new calendar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="c">
          <div class="modal-body">
            <div class="alert alert-success" style="display: none;">
              <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>Calendar is being created. Wait a moment!
            </div>
              <div class="form-group">
                <label for="date">Calendar title</label>
                <input type="text" class="form-control" name="title" id="title" required>
              </div>
            </div>
          </div>
          <div class="modal-footer">
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

    $('#createCalendar').on('click', function() {
        $('#createCalendarModal').modal('show');
    });

    // page is now ready, initialize the calendar...

    $('#calendar').fullCalendar({
        googleCalendarApiKey: '{{ env('GOOGLE_API_KEY') }}',
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
        ],
        
        eventRender: function (event, element) {
            //element.html('<p><b>Occupied!</b></p>');
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
                $('#f #date').text(selectedDate);
            } else {
                alert("Can't select date in the past!")
            }
        }
    });
});
</script>
@stop