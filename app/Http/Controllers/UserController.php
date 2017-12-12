<?php
namespace App\Http\Controllers;

use App\Googl;
use App\User;
use App\Calendar;
use App\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Redirect;

class UserController extends Controller
{

   private $client;

   public function __construct(Googl $googl)
   {
        $this->client = $googl->client();
        /*$this->client->setAccessToken(session('user.token'));*/
   }
   //return our function with object and an array
   public function profile($id) {
   		//object user
   		$user = User::where('id', '=', $id)->first();
   		//array calendars
   		$calendars = Calendar::where('user_id', '=', $id)->get();
   		
   		return view('profile.profile')->with(['user' => $user, 'calendars' => $calendars]);
   }
}