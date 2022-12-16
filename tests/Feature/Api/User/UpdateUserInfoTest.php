<?php

namespace Tests\Feature\Api\User;

use App\Models\User;
use Awesome\Foundation\Traits\Tests\DataHandler;
use Awesome\Rest\Exceptions\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\Token\AbstractToken;

class UpdateUserInfoTest extends AbstractToken
{
    use DataHandler, RefreshDatabase;

    private string $route = '/api/v1/user';

    public function test_get_user_successful()
    {
        $user = User::createActiveEntity();

        $this->createUserToken($user);

        $updatedUserInfo = User::factory()->definition();

        $response = $this->post("{$this->route}/{$user->id}",
            [
                'name' => $updatedUserInfo['name'],
                'surname' => $updatedUserInfo['surname'],
                'second_name' => $updatedUserInfo['second_name'],
                'phone' => $updatedUserInfo['phone'],
                'email' => $updatedUserInfo['email']
            ],
            array_merge(
                $this->getAuthorizationHeaders(),
                $this->getUserAuthorizationHeaders()
            )
        );

        $this->checkSuccessAssert($response);
    }

    public function test_get_user_validation_error()
    {
        $user = User::createActiveEntity();

        $this->createUserToken($user);

        $updatedUserInfo = User::factory()->definition();

        $response = $this->post("{$this->route}/{$user->id}",
            [
                'name' => $updatedUserInfo['name'],
                'surname' => $updatedUserInfo['surname']
            ],
            array_merge(
                $this->getAuthorizationHeaders(),
                $this->getUserAuthorizationHeaders()
            )
        );

        $this->checkErrorAssert($response, ValidationException::SYMBOLIC_CODE);
    }
}
