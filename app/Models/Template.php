<?php

namespace App\Models;

use App\Models\Traits\BaseTrait;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use SoftDeletes, BaseTrait;

    private static string $entityName  = 'Template';

    private static string $entitiesName = 'Templates';

    private array $admin_fields = [
        'index' => [
            'id',
            'name',
            'template_file',
        ],
        'edit' => [
            'name',
            'template_file',
            'template_content',
        ]
    ];

    private $rules = [
        'name' => 'required',
        'template_file' => 'required',
        'template_content' => 'required|regex:/\{\{\{content\}\}\}/',
    ];

    private $html = [
        'template_content' => [
            'paragraphize' => false,
            'replaceDivs' => false,
            'cleanSpaces' => false,
            'minHeight' => 200,
            'plugins' => [
                'table',
                'fullscreen',
                'counter'
            ],
        ],
    ];

    public static string $layout_path = '/resources/views/site';

    public static function getFieldNames(): array
    {
        return [
            'name' => __('Name'),
            'template_file' => __('Template file'),
            'template_content' => __('Template content'),
        ];
    }

    public function templateJs(): HasMany
    {
        return $this->hasMany(TemplateJs::class);
    }

    public function templateCss(): HasMany
    {
        return $this->hasMany(TemplateCss::class);
    }

    public function template_fileSelect(): array
    {
        $files = [];
        $templates = scandir(App::basePath().self::$layout_path);

        foreach ($templates as $template) {
            if (!str_contains($template, '.blade.php')) {
                continue;
            }

            $files[str_replace('.blade.php', '', $template)] = $template;
        }

        return $files;
    }
}
