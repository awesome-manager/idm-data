<?php

namespace App\Models;

use Awesome\Foundation\Traits\Models\AwesomeModel;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use AwesomeModel;

    protected $fillable = [
        'name',
        'code',
    ];

    protected $casts = [
        'created_at',
        'updated_at'
    ];
}
