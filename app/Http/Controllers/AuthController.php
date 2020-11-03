<?php
namespace App\Http\Controllers;

use App\Http\Resources\FailedResource;
use App\Http\Resources\SuccessResource;
use App\User;
use App\Http\Services\UserService;
use Carbon\Carbon;
use App\Jobs\SendEmailAfterRegistration;
use App\Jobs\SendTokenGeneratedEmail;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','index', 'register','confirm','generate']]);
    }

    /**
     * POST /api/auth/register
     * Register a new user
     *
     * @param RegisterRequest $request
     * @return SuccessResource
     */
    public function register(UserService $userService,RegisterRequest $request)
    {
        $credentials = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => false,
            'auth_token' => Str::random(),
            'token_generated' => date('Y-m-d H:i:s', time()),
        ];
        $user = $userService->create($credentials);
        SendEmailAfterRegistration::dispatch($user);
        return new SuccessResource((object)['message' => 'Successfully registered','code' => 200,'data' => $user]);
    }

    /**
     * POST /api/auth/login
     * Login user
     *
     * @param Request $request
     *  @return FailedResource|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!$token = auth('api')->attempt($credentials)) {
            return new FailedResource((object)['error' => 'Wrong login/password.Try again']);
        }
        else {
            return $this->respondWithToken($token);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return SuccessResource
     */
    public function logout()
    {
        auth('api')->logout();
        return new SuccessResource((object)['message' => 'Successfully Log out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     *  @return FailedResource|SuccessResource|\Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user = $this->guard()->user();
        if($user->status && $user->deact_message !== 'success' && $user->role !=='superadmin'){
            return new FailedResource((object)['error' => 'Please confirm your registration with the link sending to your email and then Login']);
        }
        elseif (!$user->status && $user->deact_message && $user->role !=='superadmin'){
            return new FailedResource((object)['error' => $user->deact_message.' Please contact support to solve this problem.']);
        }
        elseif(!$user->status){
            return new FailedResource((object)['error' => 'Please check your email for confirm registration']);
        }
        else{
            return response()->json([
                'code' => 200,
                'token' => $token,
                'user' => $user,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]);
        }
    }

    /**
     * POST /api/auth/confirmation/{token}
     * Confirm user Authentication Token
     *
     * @param UserService $userService
     * @param  $token
     * @return FailedResource|SuccessResource
     */
    public function confirm(UserService $userService,$token)
    {
        $user = User::where('auth_token', $token)->first();
        if($user){
            $end = Carbon::now();
            $start = Carbon::parse($user->token_generated);
            $dif_time = $end->diffInHours($start);
            if($user->status){
                $credentials = [
                    'auth_token' => ""
                ];
                $userService->update($credentials,$user->id);
                return new FailedResource((object)['error' => 'Registration confirmed. Auth token not found','data' => $user]);
            }
            else {
                if($dif_time > 24){
                    $credentials = [
                        'status' => false
                    ];
                    $userService->update($credentials,$user->id);
                    return new FailedResource((object)['error' => 'Auth Token time expired']);
                }else {
                    $credentials = [
                        'status' => true,
                        'deact_message' =>'success'
                    ];
                    $userService->update($credentials,$user->id);
                    return new SuccessResource((object)['message' => 'Your Registration confirmed,please log in to your account','data' => $user]);
                }
            }
        }
    }
    /**
     * POST /api/auth/generate
     * Generate new  user Authentication Token
     *
     * @param UserService $userService
     * @param  Request $request
     * @return SuccessResource
     */
    public function generate(UserService $userService,Request $request){
        $user = User::where('auth_token', $request->token)->first();
        $credentials = [
            'auth_token' => Str::random(),
            'token_generated' => date('Y-m-d H:i:s', time()),
            'status' => "Inactive"
        ];
        $user = $userService->update($credentials,$user->id);
        SendTokenGeneratedEmail::dispatch($user);
        return new SuccessResource((object)['message' => 'Auth token generated,check Email for Confirmation']);
    }
    public function guard()
    {
        return \Auth::Guard('api');
    }

}
