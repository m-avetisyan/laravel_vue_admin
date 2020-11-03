<?php


namespace App\Http\Services;

use App\User;
use App\Http\Contracts\UserInterface;

class UserService implements UserInterface
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index(){
        return  User::all();
    }
    public function create($credentials)
    {
        return $this->user->create($credentials);
    }

    public function update($credentials, $id)
    {
        return $this->user->where('id', $id)->update($credentials);
    }
    public function show($id)
    {
        return $this->user->where('id', $id)->first();
    }

}
