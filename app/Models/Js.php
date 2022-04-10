<?php

namespace App\Models;

use App\Models\Traits\BaseTrait;
use App\Models\Traits\FileTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Js extends Model
{
    use SoftDeletes, BaseTrait, FileTrait;

    private static string $entityName  = 'JS';

    private static string $entitiesName = 'JS';

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

    const POS_HEAD  = 1;
    const POS_BEGIN = 2;
    const POS_END   = 3;

    public static array $positions = [
        self::POS_HEAD => ['<head>', '</head>'],
        self::POS_BEGIN => ['<body>', ''],
        self::POS_END => ['CONTENT', '</body>'],
    ];

    protected static function booted(): void
    {
        static::updating(function($js) {
            if ((int)$js->type === $js::TYPE_EXTERNAL && $js->isDirty('type')) {
                $js->content = '';
            } elseif ((int)$js->type === $js::TYPE_INTERNAL && $js->isDirty('type')) {
                $js->deleteFile($js->path);
                $js->path = '';
            } elseif ((int)$js->type === $js::TYPE_EXTERNAL && $js->isClean('type') && $js->isDirty('path')) {
                $js->deleteFile($js->getOriginal('path'));
            }
        });

        static::forceDeleted(function($js) {
            if ((int)$js->type === $js::TYPE_EXTERNAL) {
                $js->deleteFile($js->path);
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
