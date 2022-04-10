<?php

namespace App\Models;

use App\Models\Traits\BaseTrait;
use App\Models\Traits\FileTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Css extends Model
{
    use SoftDeletes, BaseTrait, FileTrait;

    private static string $entityName  = 'CSS';

    private static string $entitiesName = 'CSS';

    private array $admin_fields = [
        'index' => [
            'id',
            'name',
            'type',
            'path',
        ],
        'edit' => [
            'name',
            'type',
            'path',
            'content',
        ]
    ];

    private $rules = [
        'name' => 'required',
        'type' => 'required',
    ];

    public $code = [
        'content' => true,
    ];

    const TYPE_INTERNAL = 0;
    const TYPE_EXTERNAL = 1;

    protected static function booted(): void
    {
        static::updating(function($css) {
            if ((int)$css->type === $css::TYPE_EXTERNAL && $css->isDirty('type')) {
                $css->content = '';
            } elseif ((int)$css->type === $css::TYPE_INTERNAL && $css->isDirty('type')) {
                $css->deleteFile($css->path);
                $css->path = '';
            } elseif ((int)$css->type === $css::TYPE_EXTERNAL && $css->isClean('type') && $css->isDirty('path')) {
                $css->deleteFile($css->getOriginal('path'));
            }
        });

        static::forceDeleted(function($css) {
            if ((int)$css->type === $css::TYPE_EXTERNAL) {
                $css->deleteFile($css->path);
            }
        });
    }

    public static function getFieldNames(): array
    {
        return [
            'name'    => __('Name'),
            'type'    => __('Type'),
            'path'    => __('Path'),
            'content' => __('Content')
        ];
    }

    public function typeSelect(): array
    {
        return [
            self::TYPE_INTERNAL => __('Internal'),
            self::TYPE_EXTERNAL => __('External'),
        ];
    }

    public function pathTableView(): string
    {
        return $this->path ? '<a href="'.$this->path.'" target="_blank">'.$this->path.'</a>' : '';
    }
}
