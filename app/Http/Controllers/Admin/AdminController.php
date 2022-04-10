<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Helpers\ModelsHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    protected ?Model $model = null;

    protected ?self $controller = null;

    protected ?string $section = null;

    protected array $actions = [
        'edit' => [
            'type' => 'get',
            'icon' => 'pencil',
            'title' => 'Edit',
        ],
        'delete' => [
            'type' => 'post',
            'icon' => 'remove',
            'title' => 'Delete',
            'confirm' => 'Are you sure you want to delete this item?',
        ],
    ];

    public function setModel($model)
    {
        $this->model = new $model;
        return $model;
    }

    public function setSection(?string $section): void
    {
        $this->section = $section;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function page(
        Request $request,
        ?string $section = null,
        ?string $model = null,
        string $action = 'index',
        int $id = 0
    ) {
        // @todo First page
        if (empty($section) && empty($model)) {
            return redirect()->route('admin', ['section' => 'site', 'model' => 'page']);
        }

        $this->section = $section;

        $models = ModelsHelper::get();

        if (!isset($models[$model])) {
            abort(404);
        }

        $this->setModel($models[$model]['class_name']);

        $alt_controller = __NAMESPACE__."\\".$models[$model]['model_name'].'Controller';

        if (class_exists($alt_controller)) {
            $this->controller = new $alt_controller;
            $this->controller->setModel($models[$model]['class_name']);
            $this->controller->setSection($this->section);
        }

        if ($this->controller && method_exists($this->controller, $action)) {
            return $this->controller->$action($request, $id);
        }

        if (!method_exists($this, $action)) {
            abort(404);
        }

        return $this->$action($request, $id);
    }

    public function index(Request $request)
    {
        $deleted   = $request->get('deleted', false);
        $sort_key  = $request->get('sort_key', 'id');
        $sort_type = $request->get('sort_type', 'asc');
        $soft_delete = $this->model->hasSoftDelete();

        if ($deleted && $soft_delete) {
            $query = $this->model::onlyTrashed();
        } else {
            $query = $this->model->select('*');
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
        ]);
    }

    public function edit(Request $request, int $id = 0)
    {
        if ($id === 0) {
            $item = $this->model;
        } else {
            $item = $this->model::findOrFail($id);
        }

        $fields = $item->getPageFields('edit');

        if ($request->isMethod('post')) {
            $save_fields = $request->only(array_keys($fields));

            foreach ($save_fields as $save_field => $save_value) {
                $item->$save_field = $save_value;
            }

            $validator = Validator::make($request->all(), $item->getRules());

            if ($validator->fails()) {
                foreach ($fields as $index_field => $field_data) {
                    if (!$field_data['rel']) {
                        continue;
                    }

                    $save_fields[$index_field.'_text_value'] = $request->get($index_field.'_text_value', '');
                }

                return back()->withErrors($validator)->withInput($save_fields);
            }

            $item->save();

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
        ]);
    }

    public function delete(Request $request, int $id)
    {
        $hasSoftDelete = $this->model->hasSoftDelete();

        if ($hasSoftDelete) {
            $item = $this->model::withTrashed()->findOrFail($id);
        } else {
            $item = $this->model::findOrFail($id);
        }

        if ($hasSoftDelete && $item->trashed()) {
            $deleted = $item->forceDelete();
        } else {
            $deleted = $item->delete();
        }

        if ($deleted) {
            $request->session()->flash('flash-message', [
                'status' => 'success',
                'message' => __('Record has been deleted'),
            ]);
        }

        return back();
    }

    public function restore(Request $request, int $id)
    {
        $item = $this->model::withTrashed()->findOrFail($id);
        $restored = $item->restore();

        if ($restored) {
            $request->session()->flash('flash-message', [
                'status' => 'success',
                'message' => __('Record has been restored'),
            ]);
        }

        return back();
    }

    public function autocomplete(Request $request, int $id = 0): JsonResponse
    {
        $data = $request->only('table', 'field', 'term');

        $model_info = ModelsHelper::getModelByTable($data['table']);

        if ($model_info === null) {
            abort(404);
        }

        $model = $model_info['class_name'];
        $query = $model::where('name', 'ilike', '%'.$data['term'].'%');

        if ($id !== 0 && $this->model::getModelNameIdx() === $model_info['model_idx']) {
            $query->where('id', '!=', $id);
        }

        $items = $query->limit(15)->get();

        $response = [
            [
                'id' => '',
                'value' => '----'.__('Delete').'----',
            ]
        ];

        foreach ($items as $item) {
            $response[] = [
                'id' => $item->id,
                'value' => $item->name,
            ];
        }

        return response()->json($response);
    }

    protected function editView(\Illuminate\Database\Eloquent\Model $item, int $id, array $fields)
    {
        return View::first([
            'admin.'.$this->model::getModelNameIdx().'_edit',
            'admin.edit',
        ], [
            'title'  => $this->model->getEntitiesName().': '.__($id ? 'Edit' : 'Add'),
            'item'   => $item,
            'fields' => $fields,
            'section' => $this->section,
        ]);
    }
}
