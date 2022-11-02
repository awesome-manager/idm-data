<?php namespace App\Exceptions;

use Awesome\Rest\Exceptions\AbstractException;

class UserRequestErrorException extends AbstractException
{
    const SYMBOLIC_CODE = 'user_request_error_exception';
}
