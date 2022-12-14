<?php

namespace App\IdmData\Passport\Guards;

use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\PassportUserProvider;
use Laravel\Passport\Guards\TokenGuard as BaseTokenGuard;

class TokenGuard extends BaseTokenGuard
{
    public function user(): mixed
    {
        $serviceClient = $this->client();
        $this->replaceRequestTokens();
        $userClient = $this->client();

        if ($this->validateClients($serviceClient, $userClient)) {
            $this->setUserProvider($userClient);

            return parent::user();
        }

        return null;
    }

    private function validateClients(?Client $serviceClient, ?Client $userClient): bool
    {
        return $serviceClient && $userClient && $serviceClient->id === $userClient->id;
    }

    private function setUserProvider($userClient)
    {
        if (!empty(config("auth.providers.{$userClient->provider}", []))) {
            $this->provider = new PassportUserProvider(
                Auth::createUserProvider($userClient->provider),
                $userClient->provider
            );
        }
    }

    private function replaceRequestTokens(): void
    {
        $userTokenHeader = config('idm.user_token_header');

        $userToken = trim($this->request->header($userTokenHeader, ''));

        if (!Str::startsWith($userToken, 'Bearer ')) {
            $userToken = "Bearer $userToken";
        }

        $this->request->headers->set($userTokenHeader, '');
        $this->request->headers->set('Authorization', $userToken);
    }
}
