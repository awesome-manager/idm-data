<?php

namespace App\Http\Controllers\Api\User;

use App\Exceptions\User\IncorrectUserException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UpdateUserInfoRequest;
use App\Http\Resources\Api\User\{UpdateUserResource, UserResource};
use App\IdmData\Contracts\Services\UserService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUserInfo()
    {
        return response()->jsonResponse((new UserResource(Auth::user()))->toArray());
    }

    public function updateUserInfo(
        UpdateUserInfoRequest $request,
        UserService           $service,
        string                $id
    )
    {
        if (Auth::user()->getAuthIdentifier() === $id) {
            return response()->jsonResponse(
                (new UpdateUserResource($service->updateUserInfo($id, $request->validated())))->toArray()
            );
        }

        throw new IncorrectUserException();
    }
}
