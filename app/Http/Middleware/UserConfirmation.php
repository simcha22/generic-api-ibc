<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserConfirmation
{
    use ApiResponser;

    public function handle(Request $request, Closure $next): \Illuminate\Http\JsonResponse
    {
        if (
            $request->user_name == $request->user()->user_name &&
            $request->user()->password == md5($request->user_name . $request->password)
        ) {
            return $next($request);
        } else {
            return $this->rejected($request->ip(), 200);
        }
    }
}
