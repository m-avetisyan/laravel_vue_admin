<?php


namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class RefreshToken
{

    public function handle($request, Closure $next)
    {
        try
        {
            if (! $user = JWTAuth::parseToken()->authenticate() )
            {
                return response()->json([
                    'code'   => 101, // means auth error in the api,
                   'response' => null // nothing to show
                 ]);
            }
        }
        catch (TokenExpiredException $e)
        {
            // If the token is expired, then it will be refreshed and added to the headers
            try
            {
                $refreshed = JWTAuth::refresh(JWTAuth::getToken());
                $user = JWTAuth::setToken($refreshed)->toUser();
                header('Authorization: Bearer ' . $refreshed);
            }
            catch (JWTException $e)
            {
                return response()->json([
                    'code'   => 103, // means not refreshable
                   'response' => $e->getMessage() // nothing to show
                 ]);
            }
        }
        catch (JWTException $e)
        {
            return response()->json([
                'code'   => 101, // means auth error in the api,
                   'response' => null // nothing to show
            ]);
        }
        // Login the user instance for global usage
        Auth::login($user, false);

        return  $next($request);
    }


}
