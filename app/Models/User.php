<?php

namespace App\Models;

use Awesome\Foundation\Traits\Models\AwesomeModel;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Hash;

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

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function findAndValidateForPassport(string $phone, string $password): ?Model
    {
        if (
            !empty($model = $this->newQuery()->where('phone', $phone)->first()) &&
            Hash::check($password, $model->password)
        ) {
            return $model;
        }

        return null;
    }

    public function getAuthIdentifier(): string
    {
        return $this->id;
    }
}
