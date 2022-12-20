<?php

namespace App\Http\Controllers\Managements;

use App\Http\Controllers\Controller;
use App\Services\UserServices;
use Illuminate\Validation\Rules;

class UserPasswordController extends Controller
{

    public function update(UserServices $userServices, int $id)
    {
        request()->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $userServices->updatePassword($id);

        return response()->noContent();
    }
}