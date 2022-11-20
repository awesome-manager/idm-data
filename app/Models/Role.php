<?php

namespace App\Models;

use Awesome\Foundation\Traits\Models\AwesomeModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function accessGroups(): BelongsToMany
    {
        $relatedTable = (new AccessGroup())->getTable();

        return $this->belongsToMany(
            AccessGroup::class,
            'role_access_group',
            'role_code',
            'access_group_code',
            'code',
            'code'
        )->where("{$relatedTable}.is_active", true);
    }
}
