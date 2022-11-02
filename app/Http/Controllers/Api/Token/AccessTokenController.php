<?php

namespace App\Http\Controllers\Api\Token;

use Illuminate\Http\Response;
use Laravel\Passport\Http\Controllers\AccessTokenController as BaseAccessTokenController;
use Psr\Http\Message\ServerRequestInterface;

class AccessTokenController extends BaseAccessTokenController
{
    private string $clientGrantType = 'client_credentials';
    private string $userGrantType = 'password';

    public function getClientToken(ServerRequestInterface $request): Response
    {
        $request = $request->withParsedBody(array_merge(
            $request->getParsedBody(),
            [
                'grant_type' => $this->clientGrantType,
                'scope' => 'private'
            ]
        ));

        return $this->issueToken($request);
    }

    public function getUserToken(ServerRequestInterface $request): Response
    {
        $request = $request->withParsedBody(array_merge(
            $request->getParsedBody(),
            [
                'grant_type' => $this->userGrantType,
                'scope' => 'user'
            ]
        ));

        return $this->issueToken($request);
    }
}
