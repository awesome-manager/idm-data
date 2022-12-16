<?php

namespace App\Models;

use Awesome\Filesystem\Models\File;
use Awesome\Foundation\Traits\Models\AwesomeModel;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasOne};
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Model
{
    use AwesomeModel, HasApiTokens;

    protected $fillable = [
        'name',
        'surname',
        'second_name',
        'phone',
        'email',
        'image_id',
        'is_active'
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
        return $this->belongsToMany(Role::class, 'user_role')->withPivot(['id', 'full_access']);
    }

    public function roleFilters(): BelongsToMany
    {
        return $this->belongsToMany(
            UserRoleFilter::class,
            'user_role',
            'user_id',
            'id',
            'id',
            'user_role_id'
        );
    }

    public function image(): HasOne
    {
        return $this->hasOne(File::class, 'id', 'image_id');
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
