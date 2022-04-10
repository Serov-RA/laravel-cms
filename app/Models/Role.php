<?php

namespace App\Models;

use App\Models\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use SoftDeletes, BaseTrait;

    private static string $entityName  = 'Role';

    private static string $entitiesName = 'Roles';

    private array $admin_fields = [
        'index' => [
            'id',
            'name',
            'is_admin',
            'pid',
        ],
        'edit' => [
            'name',
            'is_admin',
            'pid',
        ]
    ];

    private $rules = [
        'name' => 'required',
        'pid' => 'required|exists:App\Models\Role,id',
    ];

    public static function getFieldNames(): array
    {
        return [
            'name' => __('Name'),
            'is_admin' => __('Administrator'),
            'pid' => __('Parent role'),
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
