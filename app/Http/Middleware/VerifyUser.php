<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

use Spatie\Permission\Models\Role;

class VerifyUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->verified != 1) {
            return redirect()->route('errors.pending');
        }

        return $next($request);
    }
}
