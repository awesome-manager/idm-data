<?php

namespace Tests\Feature\Api\Tokens;

use Laravel\Passport\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

abstract class AbstractToken extends TestCase
{
    private string $clientTokenRoute = '/api/v1/token/client';
    protected ?string $token = null;
    protected ?string $tokenType = null;

    protected function createToken(Model $client = null): TestResponse
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

    protected function getClientTokenStructure(): array
    {
        return [
            'token_type',
            'expires_in',
            'access_token'
        ];
    }

    protected function getClientValidData(string $client, string $secret): array
    {
        return [
            'client_id' => $client,
            'client_secret' => $secret
        ];
    }
}
