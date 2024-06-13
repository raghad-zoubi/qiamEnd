<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class multiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param string $Auth
     * @return Response|RedirectResponse
     * @throws AuthorizationException
     */
//    public function handle(Request $request, Closure $next,string $Auth)
//    {
//   $user = auth()->user();
//        if(!is_null($user)){
//
//            if(in_array($user->role,explode('|', $Auth)))
//                return $next($request);
//
//
//        }
//
//       return response()->json(['message'=>'UnAuth']);
//    }

    public function handle(Request $request, Closure $next, string $roles)
    {
        $user = auth()->user();
        if (is_null($user)) {
            return response()->json(['message' => 'UnAuth'], 401);
        }

        if (in_array($user->role, explode('|', $roles))) {
            return $next($request);
        }

        throw new AccessDeniedHttpException("Access denied for role: " . $user->role);
    }
}
