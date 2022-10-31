<?php

namespace App\Models;

use Awesome\Foundation\Traits\Models\AwesomeModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Model
{
    use AwesomeModel;

    protected $fillable = [
        'name',
        'surname',
        'second_name',
        'phone',
        'email',
        'password',
        'is_active',
    ];

    protected $casts = [
        'created_at',
        'updated_at'
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }
}
