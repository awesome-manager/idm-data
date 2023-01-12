<?php

namespace Tests\Feature\Api\Token;

use App\Models\User;
use Laravel\Passport\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

abstract class AbstractToken extends TestCase
{
    private string $clientTokenRoute = '/api/v1/token/client';
    private string $userTokenRoute = '/api/v1/token/user';
    protected ?string $token = null;
    protected ?string $tokenType = null;
    protected ?string $userToken = null;
    protected ?string $userTokenType = null;
    protected ?string $userRefreshToken = null;

    protected function createClientToken(Model $client = null): TestResponse
    {
        if (is_null($client)) {
            $client = $this->createClient();
        }

        $response = $this->post(
            $this->clientTokenRoute,
            $this->getClientValidData($client->id, $client->secret)
        );

        if (!empty($responseContent = json_decode($response->getContent()))) {
            $this->token = $responseContent->access_token ?? null;
            $this->tokenType = $responseContent->token_type ?? null;
        }

        return $response;
    }

    protected function createClient(array $data = []): Model
    {
        return Client::factory()->create($data);
    }

    protected function createUserClient(array $data = []): Model
    {
        return $this->createClient(array_merge($data, [
            'provider' => 'users',
            'password_client' => true
        ]));
    }

    protected function createUserToken(Model $user = null, Model $client = null): TestResponse
    {
        if (is_null($client)) {
            $client = $this->createUserClient();
        }

        $this->createClientToken($client);

        if (is_null($user)) {
            $user = $this->createUser();
        }

        $response = $this->post(
            $this->userTokenRoute,
            array_merge(
                $this->getClientValidData($client->id, $client->secret),
                $this->getUserValidData($user->phone)
            ),
            $this->getAuthorizationHeaders()
        );

        if (!empty($responseContent = json_decode($response->getContent()))) {
            $this->userToken = $responseContent->access_token ?? null;
            $this->userRefreshToken = $responseContent->refresh_token ?? null;
            $this->userTokenType = $responseContent->token_type ?? null;
        }

        return $response;
    }

    protected function createUser(array $data = []): Model
    {
        return User::factory()->create($data);
    }

    protected function getClientTokenStructure(): array
    {
        return [
            'token_type',
            'expires_in',
            'access_token'
        ];
    }

    protected function getUserTokenStructure(): array
    {
        return [
            'token_type',
            'expires_in',
            'access_token',
            'refresh_token'
        ];
    }

    protected function getAuthorizationHeaders(): array
    {
        return [
            'Authorization' => "{$this->tokenType} {$this->token}"
        ];
    }

    protected function getUserAuthorizationHeaders(string $token = null): array
    {
        $token = $token ?: $this->userToken;

        return [
            config('idm.user_token_header') => "{$this->userTokenType} {$token}"
        ];
    }

    protected function getClientValidData(string $client, string $secret): array
    {
        return [
            'client_id' => $client,
            'client_secret' => $secret
        ];
    }

    protected function getUserValidData(string $phone, string $password = null): array
    {
        return [
            'username' => $phone,
            'password' => $password ?: 'password'
        ];
    }
}
