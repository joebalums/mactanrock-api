<?php

namespace App\Http\Controllers\Managements;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Management\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserServices;

class UsersController extends Controller
{
    public function index(UserServices $userServices)
    {
        return UserResource::collection($userServices->getUsers());
    }

    public function store(UserRequest $request, UserServices $userServices)
    {
        $data = $request->validated();
        if($request->hasFile('avatar')){
            $data['avatar'] = $request->file('avatar')->store('avatars');
        }

        $user = $userServices->create($data,UserType::ADMIN);

        return UserResource::make($user);
    }

    public function update(UserRequest $request , UserServices $userServices,  int $id)
    {
        $data = $request->validated();
        if($request->hasFile('avatar')){
            $data['avatar'] = $request->file('avatar')->store('avatars');
        }

        $user = $userServices->update($data,$id);

        return UserResource::make($user);
    }

    public function delete()
    {
        //TODO delete hard or soft
    }
}