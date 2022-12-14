<?php

namespace Tests\Feature\Api\Token;

use Awesome\Foundation\Traits\Tests\DataHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RefreshTokenTest extends AbstractToken
{
    use DataHandler, RefreshDatabase;

    private string $route = '/api/v1/token/refresh';
    private string $customRefreshToken = 'z1x2c3';

    public function test_refresh_token_successful(): void
    {
        $client = $this->createUserClient();

        $response = $this->createUserToken(null, $client);

        $this->checkAssert(
            $response,
            $this->getUserTokenStructure()
        );

        $response->assertJson(['token_type' => 'Bearer']);

        $refreshResponse = $this->post(
            $this->route,
            array_merge(
                $this->getClientValidData($client->id, $client->secret),
                ['refresh_token' => $this->userRefreshToken]
            ),
        );

        $this->checkAssert(
            $refreshResponse,
            $this->getUserTokenStructure()
        );

        $refreshResponse->assertJson(['token_type' => 'Bearer']);
    }

    public function test_refresh_token_error(): void
    {
        $client = $this->createUserClient();

        $response = $this->createUserToken(null, $client);

        $this->checkAssert(
            $response,
            $this->getUserTokenStructure()
        );

        $response->assertJson(['token_type' => 'Bearer']);

        $refreshResponse = $this->post(
            $this->route,
            array_merge(
                $this->getClientValidData($client->id, $client->secret),
                ['refresh_token' => $this->customRefreshToken]
            ),
        );

        $refreshResponse->assertStatus(401)
            ->assertJsonStructure($this->getErrorStructure())
            ->assertJson(['error' => 'invalid_request']);
    }

    private function getErrorStructure(): array
    {
        return [
            'error',
            'error_description',
            'message',
        ];
    }
}
