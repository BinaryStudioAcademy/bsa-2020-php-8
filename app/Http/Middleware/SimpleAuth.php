<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use App\Services\Contracts\AuthService;
use Illuminate\Support\Facades\Auth;

class SimpleAuth
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            if (Auth::user()) {
                return $next($request);
            }

            $this->authService->auth($request->headers->get('WWW-Authenticate') ?: '');

            return $next($request);
        } catch (\LogicException $e) {
            return response()->json([ "error" => $e->getMessage() ], 403);
        } catch (\Exception $e) {
            return response()->json([ "error" => $e->getMessage() ], 403);
        }
    }
}
