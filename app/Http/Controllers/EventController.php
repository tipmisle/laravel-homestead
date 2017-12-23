<?php
namespace App\Http\Controllers;

use App\Googl;
use App\User;
use App\Calendar;
use App\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Redirect;
use Auth;

class EventController extends Controller
{

   private $client;
   private $token;

   //constructor
   public function __construct(Googl $googl)
   {
        $this->client = $googl->client();
        //closure based middleware
        $this->middleware(function ($request, $next) {
            //set our access token
            $this->client->setAccessToken(Auth::user()->token);
            return $next($request);
        });
   }
   //function to create event on our calendar
   public function doCreateEvent(Event $evt, Request $request)
   {
        $this->validate($request, [
            'summary' => 'required'
        ]);

        $date = $request->date;
        $title = $request->summary;
        $calendar_id = $request->calendarid;
        $start = $request->time_s;
        $end = $request->time_e;

        $start_datetime = Carbon::createFromFormat('d.m.Y H:i', $date.$start);
        $end_datetime = Carbon::createFromFormat('d.m.Y H:i', $date.$end);

        $cal = new \Google_Service_Calendar($this->client);
        $event = new \Google_Service_Calendar_Event();
        $event->setSummary($title);

        $start = new \Google_Service_Calendar_EventDateTime();
        $start->setDateTime($start_datetime->toAtomString());
        $event->setStart($start);
        $end = new \Google_Service_Calendar_EventDateTime();
        $end->setDateTime($end_datetime->toAtomString());
        $event->setEnd($end);

        //attendee
        /*if (Auth::check()) {
            $attendees = [];
            $attendee_name = Auth::user()->first_name.' '.Auth::user()->last_name;
            $attendee_email = Auth::user()->email;

            $attendee = new \Google_Service_Calendar_EventAttendee();
            $attendee->setEmail($attendee_email);
            $attendee->setDisplayName($attendee_name);
            $attendees[] = $attendee;

            $event->attendees = $attendees;
        }*/

        $created_event = $cal->events->insert($calendar_id, $event);

        /*$evt->title = $title;
        $evt->calendar_id = $calendar_id;
        $evt->event_id = $created_event->id;
        $evt->datetime_start = $start_datetime->toDateTimeString();
        $evt->datetime_end = $end_datetime->toDateTimeString();
        $evt->save();*/

        return redirect('/calendar/sync');
   }

   //function for user to create event on other users calendar
   public function profileCreateEvent(Event $evt, Request $request)
   {
        $this->validate($request, [
            'summary' => 'required'
        ]);

        //set our variables from our create event form
        $date = $request->date;
        $title = $request->summary;
        $calendar_id = $request->calendarid;
        $start = $request->time_s;
        $end = $request->time_e;
        $user_id = $request->userid;

        //create carbon datetimes
        $start_datetime = Carbon::createFromFormat('d.m.Y H:i', $date.$start);
        $end_datetime = Carbon::createFromFormat('d.m.Y H:i', $date.$end);

        $cal = new \Google_Service_Calendar($this->client);
        $event = new \Google_Service_Calendar_Event();
        $event->setSummary($title);

        $start = new \Google_Service_Calendar_EventDateTime();
        $start->setDateTime($start_datetime->toAtomString());
        $event->setStart($start);
        $end = new \Google_Service_Calendar_EventDateTime();
        $end->setDateTime($end_datetime->toAtomString());
        $event->setEnd($end);

        $user = User::where('id', '=', $user_id)->first();

        //attendee
        $attendees = [];
        $attendee_name = $user->first_name.' '.$user->last_name;
        $attendee_email = $user->email;

        $attendee = new \Google_Service_Calendar_EventAttendee();
        $attendee->setEmail($attendee_email);
        $attendee->setDisplayName($attendee_name);
        $attendees[] = $attendee;

        $event->attendees = $attendees;

        $created_event = $cal->events->insert($calendar_id, $event);

        //insert our event in database
        $evt->title = $title;
        $evt->calendar_id = $calendar_id;
        $evt->event_id = $created_event->id;
        $evt->datetime_start = $start_datetime->toDateTimeString();
        $evt->datetime_end = $end_datetime->toDateTimeString();
        $evt->save();

        return redirect('/calendar/sync');

   }

}