<?php

namespace App\Exceptions;

use Awesome\Rest\Exceptions\AbstractException;

class UserUnauthorizedException extends AbstractException
{
    const SYMBOLIC_CODE = 'user_unauthorized_exception';
}
