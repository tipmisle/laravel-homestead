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

class CalendarController extends Controller
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

   //return dashboard with our name
   public function index(Request $request)
   {
        return view('dashboard')->with(["first" => session('user.first_name'), "last" => session('user.last_name')]);
   }

   //creating calendar
   public function createCalendar(Request $request, Calendar $calendar)
   {
        //validating request
        $this->validate($request, [
            'title' => 'required|min:4'
        ]);

        //getting few variables for our function
        $title = $request->input('title');
        $timezone = env('APP_TIMEZONE');

        //creating new google service calendar object
        $cal = new \Google_Service_Calendar($this->client);

        //creating new google service calendar object
        $google_calendar = new \Google_Service_Calendar_Calendar($this->client);
        $google_calendar->setSummary($title);
        $google_calendar->setTimeZone($timezone);

        $created_calendar = $cal->calendars->insert($google_calendar);

        $calendar_id = $created_calendar->getId();

        //saving the calendar to our database
        $calendar->user_id = session('user.id');
        $calendar->title = $title;
        $calendar->calendar_id = $calendar_id;
        $calendar->save();

        return redirect('/calendar/sync');
   }

   //Sync all of our calendars and events

   //TO DO: checking if event on our calendar exist and act apropriatelly
   public function syncCalendar(Calendar $calendar)
   {    
        $user_id = Auth::id();
        //First we clear events so we can get fresh data everytime
        Event::where('user_id', '=', Auth::id())->delete();
        Calendar::where('user_id', '=', Auth::id())->delete();


        $service = new \Google_Service_Calendar($this->client);
        //return user id with Auth object

        $calendars = $service->calendarList->listCalendarList();

        //Populate array with our google calendars
        $page_data = [
            'calendars' => $calendars
        ];

        //Iterate through array of our calendars
        foreach ($calendars as $calendar) {
            //set few of our variables
            $user_id = Auth::id();
            $calendar_id = $calendar->id;
            $base_timezone = env('APP_TIMEZONE');
            $sync_token = $calendar->nextSyncToken;
            $g_calendar = $service->calendars->get($calendar_id);
            $calendar_timezone = $g_calendar->getTimeZone();

            //check if synced calendar already exists...
            if (Calendar::where('calendar_id', '=', $calendar_id)->exists()) {
                //if it does, we do nothing...
            } else {
                //if it does not, we create it                 
                $c = new Calendar;
                $c->user_id = session('user.id');
                $c->title = $calendar->summary;
                $c->calendar_id = $calendar->id;
                $c->sync_token = $calendar->nextSyncToken;
                $c->save();

            }

            //gather our events which match in field calendar_id
            $events = Event::where('calendar_id', '=', $calendar_id)
                ->pluck('event_id')
                ->toArray();

            //parameters for I do not know what
            $params = [
                'showDeleted' => true,
                'timeMin' => Carbon::now()
                    ->setTimezone($calendar_timezone)
                    ->toAtomString()
            ];

            if (!empty($sync_token)) {
                $params = [
                    'syncToken' => $sync_token
                ];
            }
            //list our events in array below
            $googlecalendar_events = $service->events->listEvents($calendar_id);
                //iterate through events
                foreach ($googlecalendar_events as $event) {
                    //parsing the start
                    $g_datetime_start = Carbon::parse($event->getStart()->getDateTime())
                            ->tz($calendar_timezone)
                            ->setTimezone($base_timezone)
                            ->format('Y-m-d H:i:s');
                    //parsing the end       
                    $g_datetime_end = Carbon::parse($event->getEnd()->getDateTime())
                            ->tz($calendar_timezone)
                            ->setTimezone($base_timezone)
                            ->format('Y-m-d H:i:s');                    
                    //check if synced event already exists...
                    if (Event::where('event_id', '=', $event->id)->exists()) {
                        //if it does we do nothing
                    } else {
                        //else we create the event and save it to our base
                        $e = new Event;
                        $e->title = $event->summary;
                        $e->calendar_id = $calendar->id;
                        $e->user_id = $user_id;
                        $e->event_id = $event->id;
                        $e->datetime_start = $g_datetime_start;
                        $e->datetime_end = $g_datetime_end;
                        $e->save();

                    }
                }
    }
    //returning dashboard with our coresponding calendars
    return view('dashboard')->with(["first" => session('user.first_name'), "last" => session('user.last_name'), "calendars" => $page_data]);    
}

}