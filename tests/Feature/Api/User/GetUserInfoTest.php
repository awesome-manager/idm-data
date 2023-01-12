<?php

namespace Tests\Feature\Api\User;

use App\Exceptions\UserUnauthorizedException;
use App\Models\User;
use Awesome\Foundation\Traits\Tests\DataHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\Token\AbstractToken;

class GetUserInfoTest extends AbstractToken
{
    use DataHandler, RefreshDatabase;

    private string $route = '/api/v1/user';
    private string $customToken = 'z1x2c3';

    public function test_get_user_successful()
    {
        $user = User::createActiveEntity();

        $this->createUserToken($user);

        $response = $this->get($this->route, array_merge(
            $this->getAuthorizationHeaders(),
            $this->getUserAuthorizationHeaders()
        ));

        $this->checkAssert($response, $this->getUserStructure());
    }

    public function test_get_user_unauthorized_exception(): void
    {
        $user = User::createActiveEntity();

        $this->createUserToken($user);

        $response = $this->get($this->route, array_merge(
            $this->getAuthorizationHeaders(),
            $this->getUserAuthorizationHeaders($this->customToken)
        ));

        $this->checkErrorAssert($response, UserUnauthorizedException::SYMBOLIC_CODE);
    }

    private function getUserStructure(): array
    {
        return [
            'error',
            'content' => [
                'id',
                'name',
                'surname',
                'second_name',
                'phone',
                'email',
                'roles'
            ]
        ];
    }
}
