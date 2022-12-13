<?php

namespace App\Models;

use Awesome\Foundation\Traits\Models\AwesomeModel;
use Illuminate\Database\Eloquent\Model;

class UserRoleFilter extends Model
{
    use AwesomeModel;

    protected $fillable = [
        'user_role_id',
        'entity_type',
        'entity_id'
    ];
}
