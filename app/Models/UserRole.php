<?php

namespace App\Models;

use Awesome\Foundation\Traits\Models\AwesomeModel;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use AwesomeModel;

    protected $table = 'user_role';

    protected $fillable = [
        'user_id',
        'role_id',
        'full_access'
    ];
}
