<?php

namespace App\Http\Requests\Api\User;

use Awesome\Rest\Requests\AbstractFormRequest;

class CreateUserImageRequest extends AbstractFormRequest
{
    public function rules(): array
    {
        return [
            'dynamicPartUrl.id' => 'required|uuid',
            'image' => 'required|file'
        ];
    }
}
