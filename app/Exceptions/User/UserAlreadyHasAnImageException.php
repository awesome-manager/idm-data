<?php

namespace App\Exceptions\User;

use Awesome\Rest\Exceptions\AbstractException;

class UserAlreadyHasAnImageException extends AbstractException
{
    const SYMBOLIC_CODE = 'user_already_has_an_image_exception';
}
