<?php

namespace App\Models;

use Awesome\Foundation\Traits\Models\AwesomeModel;
use Illuminate\Database\Eloquent\Model;

class AccessGroupPage extends Model
{
    use AwesomeModel;

    protected $table = 'access_group_page';

    protected $fillable = [
        'access_group_code',
        'site_page_code',
        'is_active'
    ];

    protected $casts = [
        'created_at',
        'updated_at'
    ];
}
