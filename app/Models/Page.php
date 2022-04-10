<?php

namespace App\Models;

use App\Helpers\WidgetHelper;
use Illuminate\Validation\Rule;
use App\Models\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Page extends Model
{
    use SoftDeletes, BaseTrait;

    private static string $entityName  = 'Page';

    private static string $entitiesName = 'Pages';

    private array $admin_fields = [
        'index' => [
            'id',
            'name',
            'published',
            'alias',
            'nested',
        ],
        'edit' => [
            'name',
            'published',
            'pid',
            'alias',
            'content',
            'template_id',
            'meta_title',
            'meta_keywords',
            'meta_description',
        ]
    ];

    private $rules = [
        'name' => 'required',
        'template_id' => 'required',
        'alias' => 'required',
    ];

    private $html = [
        'content' => [
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

    private array $incuts = [];

    protected static string $section = '/';

    public array $breadcrumbs = [];

    public const RECURSION_LIMIT = 50;

    protected static function booted(): void
    {
        static::updating(function($page) {
            if ($page->isDirty('published') && !$page->published &&
                $page->id == Option::where(['option_key' => 'main_page'])->first()->option_value)
            {
                $page->published = true;
                Session::flash('flash-message', [
                    'status' => 'error',
                    'message' => __('This page is the site main page, it cannot be removed from publication .'),
                ]);
            }
        });

        static::deleting(function($page) {
            if ($page->id == Option::where(['option_key' => 'main_page'])->first()->option_value) {
                Session::flash('flash-message', [
                    'status' => 'error',
                    'message' => __('This page is the site main page, it cannot be deleted. Change the main page in the site options.'),
                ]);

                return false;
            }
        });

        static::restoring(function($page) {
            if ($page::where('alias', $page->alias)
                ->where('pid', $page->pid)
                ->where('id', '<>', $page->id)->count()) {
                Session::flash('flash-message', [
                    'status' => 'error',
                    'message' => __('Alias already exists in section'),
                ]);

                return false;
            }
        });
    }

    public static function getFieldNames(): array
    {
        return [
            'name' => __('Name'),
            'published' => __('Published'),
            'alias' => __('Alias'),
            'content' => __('Content'),
            'pid' => __('Parent item'),
            'template_id' => __('Template'),
            'meta_title' => __('Page title'),
            'meta_keywords' => 'Meta keywords',
            'meta_description' => 'Meta description',
            'nested' => __('Nested items'),
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'pid');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(static::class, 'pid');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function pageJs(): HasMany
    {
        return $this->hasMany(pageJs::class);
    }

    public function pageCss(): HasMany
    {
        return $this->hasMany(pageCss::class);
    }

    public function getAbsoluteUrl(): string
    {
        return $this->getParentUrl().$this->alias;
    }

    public static function getSection(): string
    {
        $parentClass = get_parent_class(static::class);

        return (isset($parentClass::$section) ? $parentClass::getSection() : '').
            (static::$section === '' ? '' : rtrim(static::$section, '/').'/');
    }

    public function getParentUrl(): string
    {
        return $this->parent ? rtrim($this->parent->getAbsoluteUrl(), '/').'/' : static::getSection();
    }

    public function aliasCustom(): string
    {
        return '<div class="input-group">
                    <div class="input-group-addon" style="font-size: 22px;">'.$this->getParentUrl().'</div>
                    <input type="text" id="page-alias" class="form-control" name="alias"
                    value="'.htmlspecialchars(old('alias') ?: $this->alias, ENT_QUOTES).'"></div>';
    }

    public function aliasTableView()
    {
        return '<a href="'.$this->getAbsoluteUrl().'" target="_blank">'.$this->getAbsoluteUrl().'</a>';
    }

    public function nestedTableView()
    {
         $count = self::where(['pid' => $this->id])->count();
         return !$count || $this->trashed() ? $count : '<a href="'.url()->current().'?pid='.$this->id.'">'.$count.'</a>';
    }

    public function getRules(): array
    {
        $this->rules['alias'] = [
            'required',
            Rule::unique($this->getTable())
                ->ignore($this->id)
                ->withoutTrashed()
                ->where(fn ($query) => $query->where('pid', $this->pid)),
        ];

        return $this->rules;
    }

    public static function findByAddress(string $path = '/', ?int $pid = NULL): ?self
    {
        if ($path === '/') {
            $main_page = Option::where('option_key', 'main_page')->first();

            if (!$main_page) {
                return null;
            }

            /** @var self $page */
            $page = self::where('published', true)->where('id', $main_page->option_value)->first();

            if (!$page) {
                return null;
            }

            return $page;
        }

        $parts = explode('/', trim($path, '/'));

        if (count($parts) === 1) {
            /** @var self $page */
            $page = self::where('published', true)
                ->where('alias', $parts[0])
                ->where('pid', $pid)->first();

            if (!$page) {
                return null;
            }

            $page->breadcrumbs[] = [
                'alias' => '',
                'title' => __('Main page'),
            ];

            $page->breadcrumbs[] = [
                'alias' => $page->alias,
                'title' => $page->name,
            ];

            return $page;
        }

        /** @var self $parent */
        $parent = self::select(['id', 'name', 'alias'])
            ->where('published', true)
            ->where('alias', $parts[0])
            ->where('pid', $pid)->first();

        if (!$parent) {
            return null;
        }

        unset($parts[0]);

        /** @var self $page */
        $page = self::findByAddress(implode('/', $parts), $parent->id);

        if (!$page) {
            return null;
        }

        $page->breadcrumbs[] = [
            'alias' => $parent->alias,
            'title' => $parent->name,
        ];

        $page->breadcrumbs[] = [
            'alias' => '',
            'title' => __('Main page'),
        ];

        $page->breadcrumbs = array_reverse($page->breadcrumbs);

        return $page;
    }

    public function getPageTitle()
    {
        $options = [];
        $option_list = Option::whereIn('option_key', ['site_title', 'site_title_position'])->get();

        foreach ($option_list as $option) {
            $options[$option->option_key] = $option->option_value;
        }

        return $options['site_title_position'] === 'before' ? $options['site_title'].' '.$this->meta_title :
            $this->meta_title.' '.$options['site_title'];
    }

    public function getHtmlContent(string $content, int $recursion_counter = 0): string
    {
        if ($recursion_counter > self::RECURSION_LIMIT) {
            return $content;
        }

        $content = $this->replaceIncuts($content, $recursion_counter);
        return $this->replaceWidgets($content, $recursion_counter);
    }

    protected function replaceIncuts(string $content, int $recursion_counter): string
    {
        preg_match_all('/\\{\\{\\{in\\|\d+\\}\\}\\}/Um', $content, $finds, PREG_SET_ORDER);

        if (!$finds) {
            return $content;
        }

        foreach ($finds AS $find) {
            $incut_id = str_replace(['{{{in|', '}}}'], '', $find[0]);

            if (!isset($this->incuts[$incut_id])) {
                $incut = Incut::find($incut_id);
                $this->incuts[$incut_id] = $incut !== null ?
                    $this->getHtmlContent($incut->content, $recursion_counter + 1) : '';
            }

            $content = str_replace('{{{in|'.$incut_id.'}}}', $this->incuts[$incut_id], $content);
        }

        return $content;
    }

    protected function replaceWidgets(string $content, int $recursion_counter): string
    {
        $widgets = WidgetHelper::get();

        foreach ($widgets as $widget_name => $widget) {
            $find_widget = str_replace('Widget', '', $widget_name);
            $regexp = "/\\{\\{\\{widget\\|".strtolower($find_widget)."(\\|\\w+=.+)*\\}\\}\\}/Us"; // *

            preg_match_all($regexp, $content, $find, PREG_SET_ORDER);

            foreach ($find AS $one_res) {
                $params = [
                    'model' => $this,
                ];

                $with_params = trim(
                    str_replace(['{{{widget|'.strtolower($find_widget), '}}}'], '', $one_res[0]),
                    '|'
                );

                if ($with_params) {
                    $find_params = explode('|', $with_params);

                    foreach ($find_params as $find_param) {
                        $arr = explode('=', trim($find_param, '|'));
                        $param_name = $arr[0];
                        unset($arr[0]);
                        $params[$param_name] = implode('=', $arr);
                    }
                }

                $widget_class = new $widget;

                $widget_content = $widget_class($params);
                $content = str_replace($one_res[0], $widget_content, $content);
                $content = $this->getHtmlContent($content, $recursion_counter + 1);
            }
        }

        return $content;
    }

    public function getPageContent(): string
    {
        $content = $this->content;
        $content = str_replace('{{{content}}}', $content, $this->template->template_content);

        return $this->getHtmlContent($content);
    }
}
