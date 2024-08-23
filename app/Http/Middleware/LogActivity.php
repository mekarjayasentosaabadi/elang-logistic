<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\LogActivity as LogActivityModel;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $response = $next($request);
        if(in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])){
            $this->logActivity($request);
        }
        return $response;
    }
    public function logActivity(Request $request){
        $user       = Auth::user();
        $uri        = $request->path();
        $method     = $request->method();

        LogActivityModel::create([
            'users_id'      => $user ? $user->id : null,
            'path'          => $uri,
            'url'           => $request->url(),
            'method'        => $method,
            'ip_address'    => $request->ip(),
            'user_agent'    => $request->header('User-Agent'),
            'created_at'    => now(),
            'description'   => json_encode($request->all())
        ]);
    }
}
