<?php

namespace App\Http\Controllers\Admin;

use App\Models\Js;
use App\Models\Css;
use App\Models\Option;
use App\Models\PageJs;
use App\Models\PageCss;
use Cocur\Slugify\Slugify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;

class PageController extends AdminController
{
    public function index(Request $request)
    {
        $deleted   = $request->get('deleted', false);
        $sort_key  = $request->get('sort_key', 'id');
        $sort_type = $request->get('sort_type', 'asc');
        $pid       = $request->get('pid', null);
        $soft_delete = $this->model->hasSoftDelete();

        $parent = $pid ? $this->model::find($pid) : null;

        if ($deleted && $soft_delete) {
            $query = $this->model::onlyTrashed();
        } else {
            $query = $this->model::where(['pid' => $pid]);
        }

        $items = $query->orderBy($sort_key, $sort_type)->paginate(30)->withQueryString();

        return View::first([
            'admin.'.$this->model::getModelNameIdx().'_index',
            'admin.index',
        ], [
            'title' => $this->model->getEntitiesName(),
            'items'  => $items,
            'deleted' => $deleted,
            'sort_key' => $sort_key,
            'sort_type' => $sort_type,
            'fields' => $this->model->getPageFields('index'),
            'actions' => $this->getActions(),
            'section' => $this->section,
            'model'   => $this->model,
            'soft_delete' => $soft_delete,
            'parent' => $parent,
        ]);
    }

    public function edit(Request $request, int $id = 0)
    {
        if ($id === 0) {
            $item = $this->model;

            if (!old('template_id')) {
                $default_template = Option::where('option_key', 'default_template')->first();

                if ($default_template) {
                    $item->template_id = (int)$default_template->option_value;
                }
            }
        } else {
            $item = $this->model::findOrFail($id);
        }

        $fields = $item->getPageFields('edit');

        /** CSS Form */
        $css_items = Css::orderBy('name')->get();
        $css_list = [];

        foreach ($css_items as $css_item) {
            $css_list[$css_item->id] = [
                'name' => $css_item->name,
                'disabled' => false,
            ];
        }

        $page_css = [];

        if (old('PageCss')) {
            $css_post_list = [];

            foreach (old('PageCss') AS $css_id => $css_data) {
                $css_post_list[$css_data['block_pos']] = $css_id;
            }

            ksort($css_post_list);

            foreach ($css_post_list as $css_pos => $css_id) {
                $css_obj = new PageCss();
                $css_obj->css_id = $css_id;
                $css_obj->block_pos = $css_pos;

                $page_css[] = $css_obj;
            }
        } elseif ($id !== 0) {
            $page_css = PageCss::where(['page_id' => $id])
                ->orderBy('block_pos')
                ->get();
        }

        foreach ($page_css AS $css_info) {
            $css_list[$css_info->css_id]['disabled'] = true;
        }
        /** End CSS Form */

        /** JS Form */
        $js_items = Js::orderBy('name')->get();
        $js_list = [];

        foreach ($js_items as $js_item) {
            $js_list[$js_item->id] = [
                'name' => $js_item->name,
                'disabled' => false,
            ];
        }

        $page_js = [];

        if (old('PageJs')) {
            $js_post_list = [];

            foreach (old('PageJs') AS $js_id => $js_data) {
                $js_post_list[$js_data['block_pos']] = [
                    'id' => $js_id,
                    'view_pos' => $js_data['view_pos'],
                ];
            }

            ksort($js_post_list);

            foreach ($js_post_list as $js_pos => $js_data) {
                $js_obj = new PageJs();
                $js_obj->js_id = $js_data['id'];
                $js_obj->block_pos = $js_pos;
                $js_obj->view_pos = $js_data['view_pos'];

                $page_js[] = $js_obj;
            }
        } elseif ($id !== 0) {
            $page_js = PageJs::where(['page_id' => $id])
                ->orderBy('block_pos')
                ->get();
        }

        foreach ($page_js AS $js_info) {
            $js_list[$js_info->js_id]['disabled'] = true;
        }
        /** End JS Form */

        if ($request->isMethod('post')) {
            $save_fields = $request->only(array_keys($fields));

            foreach ($save_fields as $save_field => $save_value) {
                $item->$save_field = $save_value;
            }

            $validator = Validator::make($request->all(), $item->getRules());

            if ($validator->fails()) {
                $save_fields = array_merge($save_fields, $request->only(['PageCss', 'PageJs']));

                foreach ($fields as $index_field => $field_data) {
                    if (!$field_data['rel']) {
                        continue;
                    }

                    $save_fields[$index_field.'_text_value'] = $request->get($index_field.'_text_value', '');
                }

                return back()->withErrors($validator)->withInput($save_fields);
            }

            $saving = $item->save();

            /** CSS Save */
            $current_items = [];

            if ($id !== 0) {
                foreach (PageCss::where(['page_id' => $item->id])->get() as $css_item) {
                    $current_items[$item->css_id] = $css_item;
                }
            }

            $save_page_css = $request->only('PageCss');

            if ($save_page_css) {
                foreach ($save_page_css['PageCss'] as $css_id => $css_data) {
                    if (isset($current_items[$css_id])) {
                        if ($current_items[$css_id]->block_pos !== $css_data['block_pos']) {
                            $current_items[$css_id]->block_pos = $css_data['block_pos'];
                            $current_items[$css_id]->save();
                        }

                        unset($current_items[$css_id]);
                    } else {
                        PageCss::create([
                            'name' => 'PageCSS',
                            'page_id' => $item->id,
                            'css_id' => $css_id,
                            'block_pos' => $css_data['block_pos'],
                        ]);
                    }
                }
            }

            foreach ($current_items AS $deleted_items) {
                $deleted_items->delete();
            }
            /** End CSS Save */

            /** JS Save */
            $current_items = [];

            if ($id !== 0) {
                foreach (PageJs::where(['page_id' => $item->id])->get() as $js_item) {
                    $current_items[$item->js_id] = $js_item;
                }
            }

            $save_page_js = $request->only('PageJs');

            if ($save_page_js) {
                foreach ($save_page_js['PageJs'] as $js_id => $js_data) {
                    if (isset($current_items[$js_id])) {
                        if ($current_items[$js_id]->block_pos !== $js_data['block_pos']
                            || $current_items[$js_id]->view_pos !== $js_data['view_pos']) {
                            $current_items[$js_id]->block_pos = $js_data['block_pos'];
                            $current_items[$js_id]->view_pos = $js_data['view_pos'];
                            $current_items[$js_id]->save();
                        }

                        unset($current_items[$js_id]);
                    } else {
                        PageJs::create([
                            'name' => 'PageJs',
                            'page_id' => $item->id,
                            'js_id' => $js_id,
                            'block_pos' => $js_data['block_pos'],
                            'view_pos' => $js_data['view_pos'],
                        ]);
                    }
                }
            }

            foreach ($current_items AS $deleted_items) {
                $deleted_items->delete();
            }
            /** End JS Save */

            if (!$request->session()->has('flash-message')) {
                $request->session()->flash('flash-message', [
                    'status' => 'success',
                    'message' => __('Record has been saved'),
                ]);
            }

            return redirect()->route('admin', [
                    'section' => $this->section,
                    'model' => $this->model::getModelNameIdx(),
                    'pid' => $item->pid,
                ],
            );
        }

        return View::first([
            'admin.'.$this->model::getModelNameIdx().'_edit',
            'admin.edit',
        ], [
            'title'  => $this->model->getEntitiesName().': '.__($id ? 'Edit' : 'Add'),
            'item'   => $item,
            'fields' => $fields,
            'section' => $this->section,
            'model'   => $this->model,
            'css_list' => $css_list,
            'page_css' => $page_css,
            'js_list' => $js_list,
            'page_js' => $page_js,
        ]);
    }

    public function alias(Request $request)
    {
        $item_id = $request->get('item_id', 0);
        $pid = (string)$request->get('pid', '');
        $title = (string)$request->get('title', '');
        $alias = (string)$request->get('alias', '');

        if ($alias !== '') {
            if (!preg_match('/^[A-z0-9_-]+$/i', $alias)) {
                $message = [
                    'status' => 'error',
                    'error'  => __('Alias is not correct'),
                ];
            } else {
                $q = $this->model::where('alias', $alias)->where('pid', $pid !== '' ? $pid : null);

                if ($item_id !== 0) {
                    $q->where('id', '<>', $item_id);
                }

                if ($q->count()) {
                    $message = [
                        'status' => 'error',
                        'error'  => __('Alias already exists in section'),
                        'parent' => $this->getParentAlias($pid),
                    ];
                } else {
                    $message = [
                        'status' => 'ok',
                        'alias'  => false,
                        'parent' => $this->getParentAlias($pid),
                    ];
                }
            }
        } else {
            $slugify = new Slugify();
            $alias   = $slugify->slugify($title);
            $cnt = 0;

            while ($this->model::where('pid', $pid !== '' ? $pid : null)
                ->where('alias',  $alias.($cnt ?: ''))
                ->where('id', $item_id ?: 0)
                ->count())
            {
                $cnt++;
            }

            $message = [
                'status' => 'ok',
                'alias'  => $alias.($cnt ?: ''),
                'parent' => $this->getParentAlias($pid),
            ];
        }

        return response()->json($message);
    }

    private function getParentAlias($pid): string
    {
        return $pid !== '' ? $this->model::find($pid)->getAbsoluteUrl().'/' : $this->model::getSection();
    }
}
