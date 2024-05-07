<?php

namespace App\Http\Controllers\Managements;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Management\UserRequest;
use App\Http\Resources\HistoryLogResource;
use App\Http\Resources\UserResource;
use App\Models\HistoryLogs;
use App\Models\Unit;
use App\Models\User;
use App\Services\UserServices;

class UsersController extends Controller
{
    public function index(UserServices $userServices)
    {
        return UserResource::collection($userServices->getUsers());
    }

    public function getModelHistory()
    {
        if (auth()->user()->user_type == 'admin' && !request('entity')) {
            $logs = HistoryLogs::query()->orderBy('performed_at', 'desc')->paginate(request('paginate', 10));
        } else {
            $model_type = 'App\Models\\' . ucfirst(request('entity'));
            $logs = HistoryLogs::query()->where('model_type', $model_type)->orderBy('performed_at', 'desc')->paginate(request('paginate', 10));
        }
        return HistoryLogResource::collection($logs);
    }

    public function store(UserRequest $request, UserServices $userServices)
    {
        $data = $request->validated();
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars');
        }

        $user = $userServices->create($data, $request->get('type'));

        return UserResource::make($user);
    }

    public function update(UserRequest $request, UserServices $userServices,  int $id)
    {
        $data = $request->validated();
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars');
        }

        $user = $userServices->update($data, $id);

        return UserResource::make($user);
    }

    public function destroy(int $id)
    {
        $user = User::query()->findOrFail($id);
        $user->delete();
        return UserResource::make($user);
    }
}
