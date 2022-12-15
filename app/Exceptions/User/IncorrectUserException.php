<?php

namespace App\Exceptions\User;

use Awesome\Rest\Exceptions\AbstractException;

class IncorrectUserException extends AbstractException
{
    const SYMBOLIC_CODE = 'incorrect_user_exception';
}
