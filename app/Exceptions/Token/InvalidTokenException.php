<?php

namespace App\Exceptions\Token;

use Awesome\Rest\Exceptions\AbstractException;

class InvalidTokenException extends AbstractException
{
    const SYMBOLIC_CODE = 'invalid_token_exception';
}
