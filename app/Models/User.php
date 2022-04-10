<?php

namespace App\Models;


use App\Models\Traits\BaseTrait;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes, BaseTrait;

    private static string $entityName  = 'User';

    private static string $entitiesName = 'Users';

    private array $admin_fields = [
        'index' => [
            'id',
            'name',
            'email',
            'phone',
            'role_id',
            'lang',
            'timezone',
        ],
        'edit' => [
            'name',
            'email',
            'lang',
            'role_id',
        ]
    ];

    private $rules = [
        'name' => 'required',
        'email' => 'required|email',
        'role_id' => 'required|exists:App\Models\Role,id',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getFieldNames(): array
    {
        return [
            'name' => __('User'),
            'email' => __('Email'),
            'password' => __('Password'),
            'phone' => __('Phone'),
            'lang' => __('Language'),
            'timezone' => __('Time zone'),
            'role_id' => __('Role'),
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function langSelect(): array
    {
        return [
            'ru_RU' => 'Русский',
            'en' => 'English',
        ];
    }
}
