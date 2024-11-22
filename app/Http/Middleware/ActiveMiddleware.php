<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ActiveMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->hasVerifiedEmail()) {
            // Redirect to the email verification notice page with an optional message
            return redirect()->route('activate.show',[Auth::user()->activation_token ?? 'no-token' ]);
        }
        if (Auth::check() && Auth::user()->is_blocked){
            return response()->view('error',['no'=>"403", 'error'=>'Your are blocked, Please contact the admin.']);
        }
        return $next($request);
    }
}
