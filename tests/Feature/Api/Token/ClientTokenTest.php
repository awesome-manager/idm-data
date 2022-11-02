<?php

namespace Tests\Feature\Api\Token;

use Awesome\Foundation\Traits\Tests\DataHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Client;

class ClientTokenTest extends AbstractToken
{
    use DataHandler, RefreshDatabase;

    private string $route = '/api/v1/token/client';

    public function test_get_token_successful(): void
    {
        $response = $this->createClientToken();

        $this->checkAssert(
            $response,
            $this->getClientTokenStructure()
        );

        $response->assertJson(['token_type' => 'Bearer']);
    }

    public function test_get_token_error(): void
    {
        $this->createClient();

        $response = $this->post($this->route, $this->getClientInvalidData());

        $response->assertUnauthorized()
            ->assertJsonStructure($this->getErrorStructure())
            ->assertJson(['error' => 'invalid_client']);
    }

    private function getClientInvalidData(): array
    {
        $data = Client::factory()->definition();

        return $this->getClientValidData(rand(1, 10), $data['secret']);
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
