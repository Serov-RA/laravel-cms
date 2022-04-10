<?php

namespace App\Models;

use Illuminate\Validation\Rule;
use App\Models\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Option extends Model
{
    use BaseTrait;

    private static string $entityName  = 'Option';

    private static string $entitiesName = 'Options';

    private array $admin_fields = [
        'index' => [
            'id',
            'name',
        ],
        'edit' => [
            'name',
            'option_key',
            'option_value',
        ]
    ];

    private $rules = [
        'name' => 'required',
        'option_key' => 'required',
    ];

    public static array $default_options = [
        'site_title' => [
            'name' => 'Common site title',
            'option_value' => 'Laravel -',
        ],
        'site_title_position' => [
            'name' => 'Common site title position',
            'option_value' => 'before',
            'values' => [
                'before' => 'Before page title',
                'after'  => 'After page title',
            ],
            'required' => true,
        ],
        'main_page' => [
            'name' => 'Main page',
            'option_value' => '',
            'relation' => 'Page',
            'required' => true,
        ],
        'default_template' => [
            'name' => 'Default template',
            'option_value' => '',
            'relation' => 'Template',
        ],
        'scheme' => [
            'name' => 'Site scheme',
            'option_value' => 'http',
            'values' => [
                'http'  => 'http://',
                'https' => 'https://',
            ],
            'required' => true,
        ],
    ];

    protected static function booted(): void
    {
        static::updating(function($option) {
            if ($option->option_key === 'main_page') {
                $main_page = Page::find((int)$option->option_value);

                if (!$main_page->published) {
                    $main_page->published = true;
                    $main_page->save();
                }
            }
        });

        static::deleting(function($option) {
            if (isset(self::$default_options[$option->option_key])) {
                Session::flash('flash-message', [
                    'status' => 'error',
                    'message' => __('This option is the main one, it cannot be deleted.'),
                ]);

                return false;
            }
        });
    }

    public static function getFieldNames(): array
    {
        return [
            'name' => __('Name'),
            'content' => __('Content'),
        ];
    }

    public function getRules(): array
    {
        if (isset(self::$default_options[$this->option_key], self::$default_options[$this->option_key]['required'])
            && self::$default_options[$this->option_key]['required']) {
            $this->rules['option_value'] = 'required';
        }

        if (empty($this->id)) {
            $this->rules['option_key'] = [
                'required',
                'unique:'.__CLASS__.',option_key',
            ];
        } else {
            $this->rules['option_key'] = [
                'required',
                Rule::unique($this->getTable())->ignore($this->id),
            ];
        }

        return $this->rules;
    }

    public function getOptionRelationValue(string $model_name): string
    {
        if (!$this->option_value) {
            return '';
        }

        $model_class = "App\\Models\\".$model_name;

        $value = $model_class::find($this->option_value);

        return $value ? $value->name : '';
    }

    public function getOptionRelationTable(string $model_name): string
    {
        $model_class = "App\\Models\\".$model_name;
        return (new $model_class)->getTable();
    }

    public function nameTableView(): string
    {
        return __($this->name);
    }
}
