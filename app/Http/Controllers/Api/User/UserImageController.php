<?php

namespace App\Http\Controllers\Api\User;

use App\Exceptions\User\IncorrectUserException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\CreateUserImageRequest;
use App\Http\Resources\Api\User\CreateUserImageResource;
use App\IdmData\Contracts\Services\UserService;
use Illuminate\Support\Facades\Auth;

class UserImageController extends Controller
{
    public function createUserImage(
        CreateUserImageRequest $request,
        UserService            $service,
        string                 $id
    )
    {
        if (Auth::user()->getAuthIdentifier() === $id) {
            return response()->jsonResponse(
                (new CreateUserImageResource($service->createUserImage($id, $request->file('image'))))->toArray()
            );
        }

        throw new IncorrectUserException();
    }

    public function updateUserImage()
    {

    }

    public function deleteUserImage()
    {

    }
}
