<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\Role;
use Symfony\Component\HttpFoundation\Response;

class PatientOnlyAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role !== Role::PATIENT) {
            return redirect('/admin');
        }

        return $next($request);
    }
}
