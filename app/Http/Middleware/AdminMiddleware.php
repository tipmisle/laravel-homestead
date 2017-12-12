<?php
namespace App\Http\Middleware;

use App\User;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (User::where('email', '=', session('user.email'))->exists()) {
            //manually logging in user, thus creating auth object which we use elsewhere
            Auth::loginUsingId(session('user.id'));
            return $next($request);
        }

        return redirect('/')
            ->with(
                'message',
                ['type' => 'danger', 'text' => 'You need to login']
            );
    }
}