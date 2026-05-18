<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {

            $user = Auth::user();

            // Check if master exists
            if ($user->master && in_array($user->master->name, ['director', 'operator', 'principal', 'hod']) &&
                $user->master->status == 1
            ) {
                return $next($request);
            }
        }

        return redirect('admin/login')->with('error', 'Unauthorized access');
    }
}
