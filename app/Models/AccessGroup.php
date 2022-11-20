<?php

namespace App\Models;

use Awesome\Foundation\Traits\Models\AwesomeModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccessGroup extends Model
{
    use AwesomeModel;

    protected $fillable = [
        'code',
        'has_filter',
        'is_active',
    ];

    protected $casts = [
        'created_at',
        'updated_at'
    ];

    public function accessGroupPages(): HasMany
    {
        $relatedTable = (new AccessGroupPage())->getTable();

        return $this->hasMany(AccessGroupPage::class, 'access_group_code', 'code')
            ->where("{$relatedTable}.is_active", true);
    }
}
