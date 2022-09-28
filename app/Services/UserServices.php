<?php

namespace App\Services;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserServices
{


    public function getUsers(int $take = 10, ?int $branch = null)
    {
        return User::query()
            ->latest()
            ->paginate(is_integer(request('paginate',12)) ?request('paginate'):0);
    }

    public function create(array $data, mixed $type): User
    {
        $user = request()->user();

        $user = new User();
        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];
        $user->middlename = $data['middlename'] ?? '';
        $user->avatar = $data['avatar'] ?? '';
        $user->contact = $data['contact'];
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->user_type = $type;
        if(is_null($user?->branch_id)){
            $user->branch_id = request()->get('branch_id');
        } else{
            $user->branch_id = $user?->branch_id;
        }

        $user->save();

        return $user;
    }

    public function update(array $data, int $id): User
    {
        $user = User::query()->findOrfail($id);
        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];
        $user->middlename = $data['middlename'] ?? '';
        $user->avatar = $data['avatar'] ?? '';
        $user->contact = $data['contact'];
        $user->email = $data['email'];
        $user->save();

        return $user;
    }

    /**
     * @throws ValidationException
     */
    public function changePassword(string $password, User $user):void
    {
        if(!Hash::check($password,$user->password))
            throw  ValidationException::withMessages([
                'old_password' => 'Invalid old password'
            ]);

        $user->password = bcrypt($password);

       $user->save();

    }
}