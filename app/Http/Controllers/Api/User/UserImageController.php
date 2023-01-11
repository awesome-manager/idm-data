<?php

namespace App\Http\Controllers\Api\User;

use App\Exceptions\User\IncorrectUserException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\{CreateUserImageRequest, DeleteUserImageRequest};
use App\Http\Resources\Api\User\{CreateUserImageResource, DeleteUserImageResource};
use App\IdmData\Contracts\Services\UserService;
use Illuminate\Support\Facades\Auth;

// TODO: Add tests for methods in this container
class UserImageController extends Controller
{
    /**
     * @throws IncorrectUserException
     */
    public function createUserImage(
        CreateUserImageRequest $request,
        UserService            $service,
        string                 $id
    )
    {
        $this->checkUserId($id);

        return response()->jsonResponse(
            (new CreateUserImageResource($service->createUserImage($id, $request->file('image'))))->toArray()
        );
    }

    /**
     * @throws IncorrectUserException
     */
    public function deleteUserImage(
        DeleteUserImageRequest $request,
        UserService            $service,
        string                 $id
    )
    {
        $this->checkUserId($id);

        return response()->jsonResponse(
            (new DeleteUserImageResource($service->deleteUserImage($id)))->toArray()
        );
    }

    /**
     * @throws IncorrectUserException
     */
    private function checkUserId(string $id): void
    {
        if (!(Auth::user()->getAuthIdentifier() === $id)) {
            throw new IncorrectUserException();
        }
    }
}
