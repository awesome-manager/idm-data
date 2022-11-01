<?php

namespace Tests\Feature\Api\Tokens;

use App\Models\User;
use Awesome\Foundation\Traits\Tests\DataHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Client;

class UserTokenTest extends AbstractToken
{
    use DataHandler, RefreshDatabase;

    private string $route = '/api/v1/token/user';


    public function test_get_token_successful(): void
    {
        $client = $this->createClient([
            'provider' => 'users',
            'password_client' => true
        ]);
        $this->createToken($client);

        $user = User::createActiveEntity();

        $response = $this->post(
            $this->route,
            array_merge(
                $this->getClientValidData($client->id, $client->secret),
                $this->getUserValidData($user->phone)
            ),
            $this->getAuthorizationHeaders()
        );

        $this->checkAssert(
            $response,
            $this->getSuccessStructure()
        );

        $response->assertJson(['token_type' => 'Bearer']);
    }

    public function test_get_token_error(): void
    {
        $client = $this->createClient([
            'provider' => 'users',
            'password_client' => true
        ]);
        $this->createToken($client);

        User::createActiveEntity();

        $response = $this->post(
            $this->route,
            array_merge(
                $this->getClientValidData($client->id, $client->secret),
                $this->getUserInvalidData()
            ),
            $this->getAuthorizationHeaders()
        );

        $response->assertStatus(400)
            ->assertJsonStructure($this->getErrorStructure())
            ->assertJson(['error' => 'invalid_grant']);
    }

    private function getAuthorizationHeaders(): array
    {
        return [
            'Authorization' => "{$this->tokenType} $this->token"
        ];
    }

    private function getUserValidData(string $phone): array
    {
        return [
            'username' => $phone,
            'password' => 'password'
        ];
    }

    private function getUserInvalidData(): array
    {
        $data = User::factory()->definition();

        return $this->getUserValidData($data['phone']);
    }

    private function getSuccessStructure(): array
    {
        return [
            'token_type',
            'expires_in',
            'access_token',
            'refresh_token'
        ];
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
