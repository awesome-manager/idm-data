<?php

namespace App\Exceptions;

use Awesome\Rest\Exceptions\AbstractException;

class ClientUnauthorizedException extends AbstractException
{
    const SYMBOLIC_CODE = 'client_unauthorized_exception';
}
