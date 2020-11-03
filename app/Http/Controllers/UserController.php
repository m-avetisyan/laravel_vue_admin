<?php

namespace App\Http\Controllers;

use App\Http\Contracts\UserInterface;
use App\Http\Resources\FailedResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{


    protected $userService;
    public function __construct(UserInterface $userService)
    {
        $this->userService = $userService;
    }
    /**
     * GET /api/users
     * Get all users
     * @return SuccessResource
     */
    public function index()
    {
        $users = $this->userService->index();
        return new SuccessResource((object)['message' => 'Successfully fetched users','code' => 200,'data' => $users]);
    }
    /**
     * GET /api/users/{id}
     * Get single user
     * @return SuccessResource
     */
    public function show($id)
    {
        $user = $this->userService->show($id);
        return new SuccessResource((object)['message' => 'Successfully fetched user','code' => 200,'data' => $user]);
    }

    /**
     * PUT /api/users/{id}
     * Update user
     *
     * @param $id
     */
    public function update(Request $request,$id)
    {

        $credentials = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        if($request->password && $request->password_confirmation){
            $this->validate($request, [
                'password' => 'confirmed',
            ]);
            $credentials['password'] = Hash::make($request->password);
        }


        $user = $this->userService->update($credentials, $id);
        if ($user) {
            $users = $this->userService->index();
            return new SuccessResource((object)['message' => 'User  updated','data' => $users]);
        }
        else {
            return new FailedResource((object)['error' => 'User can not be updated']);
        }
    }

    /**
     * Update user status
     *
     * @param $id
     */
    public function updateStatus(Request $request,$id)
    {
            $credentials = [
                'status' => $request->status,
                'deact_message' => $request->deact_message
            ];
            $user = $this->userService->update($credentials, $id);
            if ($user) {
                $users = $this->userService->index();
                return new SuccessResource((object)['message' => 'User  status updated','data' => $users]);
            }
            else {
                return new FailedResource((object)['error' => 'User status can not be updated']);
            }
    }
}
