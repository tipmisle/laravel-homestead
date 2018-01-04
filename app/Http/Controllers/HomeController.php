<?php
namespace App\Http\Controllers;

use App\Googl;
use App\User;
use App\Calendar;
use Illuminate\Http\Request;
use Session;

class HomeController extends Controller
{
    protected $redirectTo = '/';
    //homepage
   public function index()
   {
        
        return view('login');
   }

   //login function
   public function login(Googl $googl, User $user, Request $request)
   {
        if(!session()->has('from')){
            session()->put('from', url()->previous());
        }
        
        //setting our client
        $client = $googl->client();
        //authenticating user
        if ($request->has('code')) {
            $client->authenticate($request->get('code'));
            $token = $client->getAccessToken();

            $plus = new \Google_Service_Plus($client);

            $google_user = $plus->people->get('me');

            $id = $google_user['id'];

            $email = $google_user['emails'][0]['value'];
            $first_name = $google_user['name']['givenName'];
            $last_name = $google_user['name']['familyName'];

            $has_user = $user->where('email', '=', $email)->first();
            //checking if user exists in our database
            if (!$has_user) {
                //not yet registered
                $user->email = $email;
                $user->first_name = $first_name;
                $user->last_name = $last_name;
                $user->token = json_encode($token);
                $user->save();
                $user_id = $user->id;
            } else {
                $user_id = $has_user->id;
            }
            //create a session with our data
            session([
                'user' => [
                    'id' => $user_id,
                    'email' => $email,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'token' => $token,
                    'from' => url()->previous()
                ]
            ]);
            
            
           return redirect(session()->pull('from',$this->redirectTo));

        } else {
            $auth_url = $client->createAuthUrl();
            return redirect($auth_url);
        }
   }

    //logout
   public function logout(Request $request)
   {
        $request->session()->flush();
        return redirect('/')
            ->with('message', ['type' => 'success', 'text' => 'You are now logged out']);
   }
}