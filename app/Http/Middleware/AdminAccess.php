<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            // Redirect to home or show an error message
            // Using session()->flash for toast notification
            session()->flash('error_message', 'You are not allowed gandu!');
            return redirect('/'); // Redirect to home page
        }

        return $next($request);
    }
}
