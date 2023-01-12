<?php

namespace App\Http\Requests\Api\User;

use Awesome\Rest\Requests\AbstractFormRequest;

class DeleteUserImageRequest extends AbstractFormRequest
{
    public function rules(): array
    {
        return [
            'dynamicPartUrl.id' => 'required|uuid'
        ];
    }
}
