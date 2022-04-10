<?php

namespace App\Http\Controllers\Admin;

use App\Models\Js;
use App\Models\Css;
use App\Models\TemplateJs;
use App\Models\TemplateCss;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;

class TemplateController extends AdminController
{
    public function edit(Request $request, int $id = 0)
    {
        if ($id === 0) {
            $item = $this->model;
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

        $template_css = [];

        if (old('TemplateCss')) {
            $css_post_list = [];

            foreach (old('TemplateCss') AS $css_id => $css_data) {
                $css_post_list[$css_data['block_pos']] = $css_id;
            }

            ksort($css_post_list);

            foreach ($css_post_list as $css_pos => $css_id) {
                $css_obj = new TemplateCss();
                $css_obj->css_id = $css_id;
                $css_obj->block_pos = $css_pos;

                $template_css[] = $css_obj;
            }
        } elseif ($id !== 0) {
            $template_css = TemplateCss::where(['template_id' => $id])
                ->orderBy('block_pos')
                ->get();
        }

        foreach ($template_css AS $css_info) {
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

        $template_js = [];

        if (old('TemplateJs')) {
            $js_post_list = [];

            foreach (old('TemplateJs') AS $js_id => $js_data) {
                $js_post_list[$js_data['block_pos']] = [
                    'id' => $js_id,
                    'view_pos' => $js_data['view_pos'],
                ];
            }

            ksort($js_post_list);

            foreach ($js_post_list as $js_pos => $js_data) {
                $js_obj = new TemplateJs();
                $js_obj->js_id = $js_data['id'];
                $js_obj->block_pos = $js_pos;
                $js_obj->view_pos = $js_data['view_pos'];

                $template_js[] = $js_obj;
            }
        } elseif ($id !== 0) {
            $template_js = TemplateJs::where(['template_id' => $id])
                ->orderBy('block_pos')
                ->get();
        }

        foreach ($template_js AS $js_info) {
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
                $save_fields = array_merge($save_fields, $request->only(['TemplateCss', 'TemplateJs']));
                return back()->withErrors($validator)->withInput($save_fields);
            }

            $item->save();

            /** CSS Save */
            $current_items = [];

            if ($id !== 0) {
                foreach (TemplateCss::where(['template_id' => $item->id])->get() as $css_item) {
                    $current_items[$item->css_id] = $css_item;
                }
            }

            $save_template_css = $request->only('TemplateCss');

            if ($save_template_css) {
                foreach ($save_template_css['TemplateCss'] as $css_id => $css_data) {
                    if (isset($current_items[$css_id])) {
                        if ($current_items[$css_id]->block_pos !== $css_data['block_pos']) {
                            $current_items[$css_id]->block_pos = $css_data['block_pos'];
                            $current_items[$css_id]->save();
                        }

                        unset($current_items[$css_id]);
                    } else {
                        TemplateCss::create([
                            'name' => 'TemplateCSS',
                            'template_id' => $item->id,
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
                foreach (TemplateJs::where(['template_id' => $item->id])->get() as $js_item) {
                    $current_items[$item->js_id] = $js_item;
                }
            }

            $save_template_js = $request->only('TemplateJs');

            if ($save_template_js) {
                foreach ($save_template_js['TemplateJs'] as $js_id => $js_data) {
                    if (isset($current_items[$js_id])) {
                        if ($current_items[$js_id]->block_pos !== $js_data['block_pos']
                            || $current_items[$js_id]->view_pos !== $js_data['view_pos']) {
                            $current_items[$js_id]->block_pos = $js_data['block_pos'];
                            $current_items[$js_id]->view_pos = $js_data['view_pos'];
                            $current_items[$js_id]->save();
                        }

                        unset($current_items[$js_id]);
                    } else {
                        TemplateJs::create([
                            'name' => 'TemplateJs',
                            'template_id' => $item->id,
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

            $request->session()->flash('flash-message', [
                'status' => 'success',
                'message' => __('Record has been saved'),
            ]);

            return redirect()->route('admin', [
                    'section' => $this->section,
                    'model' => $this->model::getModelNameIdx()]
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
            'template_css' => $template_css,
            'js_list' => $js_list,
            'template_js' => $template_js,
        ]);
    }
}
