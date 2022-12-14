<?php

namespace Tests\Feature\Api\Token;

use App\Exceptions\Token\InvalidTokenException;
use Awesome\Foundation\Traits\Tests\DataHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RevokeUserTokenTest extends AbstractToken
{
    use DataHandler, RefreshDatabase;

    private string $route = '/api/v1/token/user';
    private string $customToken = 'z1x2c3';

    public function test_revoke_token_successful(): void
    {
        $this->createUserToken();

        $response = $this->delete(
            $this->route,
            [],
            array_merge(
                $this->getAuthorizationHeaders(),
                $this->getUserAuthorizationHeaders()
            ),
        );

        $this->checkSuccessAssert($response);
    }

    public function test_revoke_token_invalid_token_exception(): void
    {
        $this->createUserToken();

        $response = $this->delete(
            $this->route,
            [],
            array_merge(
                $this->getAuthorizationHeaders(),
                $this->getUserAuthorizationHeaders($this->customToken)
            ),
        );

        $this->checkErrorAssert($response, InvalidTokenException::SYMBOLIC_CODE);
    }
}
