<?php
namespace App\Http\Middleware;

use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsTeacher
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if(!$user || !Teacher::where('user_id', $user->id)->exists()){
            abort(403 , 'غير مسموح بالدخول الا كمعلم');
        }
        return $next($request);
    }
}