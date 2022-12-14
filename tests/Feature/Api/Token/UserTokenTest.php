<?php

namespace Tests\Feature\Api\Token;

use App\Models\User;
use Awesome\Foundation\Traits\Tests\DataHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTokenTest extends AbstractToken
{
    use DataHandler, RefreshDatabase;

    private string $route = '/api/v1/token/user';

    public function test_get_token_successful(): void
    {
        $user = User::createActiveEntity();

        $response = $this->createUserToken($user);

        $this->checkAssert(
            $response,
            $this->getUserTokenStructure()
        );

        $response->assertJson(['token_type' => 'Bearer']);
    }

    public function test_get_token_error(): void
    {
        $client = $this->createUserClient();
        $this->createClientToken($client);

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

    private function getUserInvalidData(): array
    {
        $data = User::factory()->definition();

        return $this->getUserValidData($data['phone']);
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
