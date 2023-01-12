<?php

namespace App\Http\Controllers\Api\Token;

use App\Exceptions\Token\InvalidTokenException;
use App\Http\Resources\Api\Token\RevokeUserTokenResource;
use Illuminate\Http\{JsonResponse, Response};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Passport\{RefreshTokenRepository, TokenRepository};
use Laravel\Passport\Http\Controllers\AccessTokenController as BaseAccessTokenController;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Plain;
use Psr\Http\Message\ServerRequestInterface;

class AccessTokenController extends BaseAccessTokenController
{
    private string $clientGrantType = 'client_credentials';
    private string $userGrantType = 'password';
    protected string $refreshGrantType = 'refresh_token';

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

    public function refreshAccessToken(ServerRequestInterface $request): Response
    {
        $request = $request->withParsedBody(array_merge(
            $request->getParsedBody(),
            [
                'grant_type' => $this->refreshGrantType,
                'scope' => 'user'
            ]
        ));

        return $this->issueToken($request);
    }

    /**
     * @throws InvalidTokenException
     */
    public function revokeUserToken(
        TokenRepository         $tokenRepository,
        RefreshTokenRepository  $refreshTokenRepository,
        ServerRequestInterface  $request
    ): JsonResponse
    {
        $tokenId = $this->getRequestBearerToken($request);

        $success = DB::transaction(function () use ($tokenRepository, $refreshTokenRepository, $tokenId) {
            $success = (bool)$tokenRepository->revokeAccessToken($tokenId);

            $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($tokenId);

            return $success;
        });

        return response()->jsonResponse((new RevokeUserTokenResource($success))->toArray());
    }

    /**
     * @throws InvalidTokenException
     */
    private function getRequestBearerToken(ServerRequestInterface $request): ?string
    {
        $token = $request->getHeaderLine(config('idm.user_token_header'));

        if (Str::startsWith($token, 'Bearer ')) {
            $token = Str::substr($token, 7);
        }

        if (empty($token)) {
            return null;
        }

        try {
            $token = app(Parser::class)->parse($token);
        } catch (\ParseError|InvalidTokenStructure $error) {
            throw new InvalidTokenException();
        }

        return $this->getClaim($token);
    }

    private function getClaim(Plain $token): ?string
    {
        return $token->claims()->get('jti');
    }
}
