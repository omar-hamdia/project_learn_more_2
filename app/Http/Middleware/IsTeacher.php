<?php
namespace App\Http\Middleware;

use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!auth()->check()){
            return redirect()->route('login');
        }
        $role = auth()->user()->role ?? null ;
        if($role !== 'teacher' && $role !== 'admin'){
            return redirect()->route('login');
        }
        return $next($request);







}
} 