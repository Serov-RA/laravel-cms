<x-admin.layout :title="$title" :section="$section" :model="$model::getModelNameIdx()">
    <style>
        .has-error .help-block,
        .has-error .control-label,
        .has-error .radio,
        .has-error .checkbox,
        .has-error .radio-inline,
        .has-error .checkbox-inline,
        .has-error.radio label,
        .has-error.checkbox label,
        .has-error.radio-inline label,
        .has-error.checkbox-inline label {
            color: #a94442;
        }

        .help-block {
            display: block;
            margin-top: 5px;
            margin-bottom: 10px;
            color: #737373;
        }

        .form_buttons .btn {
            border-color: silver;
        }
    </style>
    <script type="text/javascript">
        var itemId = {{ $item->id ?? 0 }};
    </script>

    <div class="row">
        <div class="col-md-3 col-xs-hidden"></div>
        <div class="col-md-6 col-xs-12">
            <form method="post">
                @csrf

                <div class="" role="tabpanel">
                    <ul id="form_tabs" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#tab_content_Page_edit"
                               id="form_tab_Page_edit"
                               role="tab"
                               data-toggle="tab"
                               aria-expanded="true"
                               class="active"
                               aria-selected="true">
                                <i class="fa fa-pencil"></i> <span class="hidden-xs">Редактирование</span>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#tab_content_Page_edit_form_CSS"
                               id="form_tab_Page_edit_form_CSS"
                               role="tab"
                               data-toggle="tab"
                               aria-expanded="true">
                                <i class="fa fa-css3"></i> <span class="hidden-xs">CSS</span>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#tab_content_Page_edit_form_JS"
                               id="form_tab_Page_edit_form_JS"
                               role="tab"
                               data-toggle="tab"
                               aria-expanded="true">
                                <i class="fa fa-file-code-o"></i> <span class="hidden-xs">JS</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">

                        <div role="tabpanel" class="tab-pane fade in active show"
                             id="tab_content_Page_edit"
                             aria-labelledby="form_tab_Page_edit"
                             data-form="Page_edit"
                             style="margin-top: -15px">
                            <div id="w0" class="x_panel">
                                <div class="x_content">
                                    @foreach ($fields as $field_key => $field_data)
                                        <div class="form-group field-{{ $item::getModelNameIdx() }}-{{ $field_key }} @error($field_key) has-error @enderror ">
                                            <label class="control-label" for="{{ $item::getModelNameIdx() }}-{{ $field_key }}">
                                                {{ $field_data['name'] }}
                                            </label>
                                            @if ($field_data['custom'])
                                                {!! $field_data['custom'] !!}
                                            @elseif ($field_data['select'])
                                                <select id="{{ $item::getModelNameIdx() }}-{{ $field_key }}"
                                                        class="form-control"
                                                        name="{{ $field_key }}">
                                                    @foreach ($field_data['select'] as $select_key => $select_value)
                                                        <option value="{{ $select_key }}"
                                                            {{ $errors->any() && old($field_key) === $select_key ? ' selected' :
                                                               ($item->$field_key === $select_key ? ' selected' : '') }}>
                                                            {{ $select_value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @elseif ($field_data['rel'])
                                                <input type="hidden"
                                                       id="relation_field_{{ $field_key }}"
                                                       name="{{ $field_key }}"
                                                       value="{{ $errors->any() ? old($field_key) : $item->$field_key }}">
                                                <div class="input-group">
                                                    <span class="input-group-addon" style="font-size: 22px;">
                                                        <i id="relation_field_addon{{ $field_key }}"
                                                           class="fa fa-{{ $field_data['rel']['value'] ? '' : 'un' }}link"></i>
                                                    </span>
                                                    <input type="text"
                                                           class="form-control relation_field"
                                                           data-field="{{ $field_key }}"
                                                           data-table="{{ $field_data['rel']['table'] }}"
                                                           autocomplete="off"
                                                           value="{{ $errors->any() ? old($field_key.'_text_value') : $field_data['rel']['value'] }}">
                                                </div>
                                            @elseif ($field_data['datetime'])
                                                <div class="input-group">
                            <span class="input-group-addon" style="font-size: 22px;">
                                <i id="relation_field_addon{{ $field_key }}" class="fa fa-calendar"></i>
                            </span>
                                                    <input type="text"
                                                           id="{{ $item::getModelNameIdx() }}-{{ $field_key }}"
                                                           class="form-control picker_datetime"
                                                           name="{{ $field_key }}"
                                                           @if ($errors->any())
                                                           value="old($field_key)"
                                                           @else
                                                           value="@datetime($item->$field_key)"
                                                           @endif
                                                           autocomplete="off">
                                                </div>
                                            @elseif ($field_data['date'])
                                                <div class="input-group">
                            <span class="input-group-addon" style="font-size: 22px;">
                                <i id="relation_field_addon{{ $field_key }}" class="fa fa-calendar"></i>
                            </span>
                                                    <input type="text"
                                                           id="{{ $item::getModelNameIdx() }}-{{ $field_key }}"
                                                           class="form-control picker_date"
                                                           name="{{ $field_key }}"
                                                           @if ($errors->any())
                                                           value="old($field_key)"
                                                           @else
                                                           value="@date($item->$field_key)"
                                                           @endif
                                                           autocomplete="off">
                                                </div>
                                                @once
                                                    @push('js')
                                                        <script src="/js/admin/jquery.datetimepicker.full.min.js"></script>
                                                    @endpush
                                                    @push('css')
                                                        <link href="/css/admin/jquery.datetimepicker.min.css" rel="stylesheet">
                                                    @endpush
                                                @endonce
                                            @elseif ($field_data['type'] == 'boolean')
                                                <input type="hidden" name="{{ $field_key }}" value="0">
                                                <input type="checkbox"
                                                       id="{{ $item::getModelNameIdx() }}-{{ $field_key }}"
                                                       name="{{ $field_key }}"
                                                       @if ($errors->any())
                                                       {{ old($field_key) ? ' checked' : '' }}
                                                       @elseif ((!$item->id && $field_data['default'] === 'true') || $item->$field_key)
                                                       checked
                                                       @endif
                                                       value="1">
                                            @elseif ($field_data['html'])
                                                <textarea name="{{ $field_key }}"
                                                          id="{{ $item::getModelNameIdx() }}-{{ $field_key }}"
                                                          class="html_redactor"
                                                          data-options='{{ json_encode($field_data['html']) }}'
                                                >{{ $errors->any() ? old($field_key) : $item->$field_key }}</textarea>
                                                @once
                                                    @push('js')
                                                        <script src="/js/admin/redactor.min.js"></script>
                                                    @endpush
                                                    @push('css')
                                                        <link href="/css/admin/redactor.css" rel="stylesheet">
                                                    @endpush
                                                @endonce
                                            @elseif ($field_data['code'])
                                                <textarea name="{{ $field_key }}"
                                                          id="{{ $item::getModelNameIdx() }}-{{ $field_key }}"
                                                          class="code_redactor"
                                                >{{ $errors->any() ? old($field_key) : $item->$field_key }}</textarea>
                                                @once
                                                    @push('js')
                                                        <script src="/lib/codemirror/lib/codemirror.js"></script>
                                                    @endpush
                                                    @push('css')
                                                        <link href="/lib/codemirror/lib/codemirror.css" rel="stylesheet">
                                                    @endpush
                                                @endonce
                                            @else
                                                <input type="text"
                                                       id="{{ $item::getModelNameIdx() }}-{{ $field_key }}"
                                                       class="form-control"
                                                       name="{{ $field_key }}"
                                                       value="{{ $errors->any() ? old($field_key) : $item->$field_key }}">
                                            @endif
                                            <div class="help-block">
                                                @error($field_key) {{ $message }} @enderror
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div role="tabpanel"
                             class="tab-pane fade"
                             id="tab_content_Page_edit_form_CSS"
                             aria-labelledby="form_tab_Page_edit_form_CSS"
                             data-form="Page_edit_form_CSS"
                             style="margin-top: -15px">
                            <div id="w1" class="x_panel">
                                <div class="x_content">

                                    <script type="text/javascript">
                                        var cssEditForm = 'pagecss';
                                        var cssEditField = 'PageCss';
                                    </script>

                                    @push('js')
                                        <script src="/js/admin/css.js"></script>
                                    @endpush

                                    <div class="form-group">
                                        <select name="css-select" id="css-select" class="form-control">
                                            <option value="" id="emp_css_sel" selected disabled>
                                                {{ __('Select value') }}
                                            </option>
                                            @foreach ($css_list as $css_id => $css_item)
                                                <option value="{{ $css_id }}"{{ $css_item['disabled'] ? ' disabled' : '' }}>
                                                    {{ $css_item['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <ul class="list-group" id="select_css_list">
                                        @foreach ($page_css as $t_css)
                                            <li class="list-group-item text-left"
                                                data-css="{{ $t_css->css_id }}"
                                                id="css_item_{{ $t_css->css_id }}">
                                                <i class="fa fa-reorder" style="cursor: move"></i> &nbsp;
                                                <span style="float: right">
                                                <button type="button"
                                                    class="close"
                                                    aria-label="Close"
                                                    data-css="{{ $t_css->css_id }}">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </span>
                                                <a href="/admin/site/css/edit/{{ $t_css->css_id }}"target="_blank">
                                                    {{ $t_css->css->name }}
                                                </a>
                                                <div class="form-group field-pagecss-{{ $t_css->css_id }}-block_pos">
                                                    <input type="hidden"
                                                           id="pagecss-{{ $t_css->css_id }}-block_pos"
                                                           class="form-control"
                                                           name="PageCss[{{ $t_css->css_id }}][block_pos]"
                                                           value="{{ $t_css->block_pos }}">
                                                </div>
                                            </li>

                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="tab_content_Page_edit_form_JS"
                             aria-labelledby="form_tab_Page_edit_form_JS"
                             data-form="Page_edit_form_JS"
                             style="margin-top: -15px">
                            <div id="w2" class="x_panel">
                                <div class="x_content">
                                    <script type="text/javascript">
                                        var jsEditForm = 'pagejs';
                                        var jsEditField = 'PageJs';
                                        var jsDefaultPos = {{ \App\Models\Js::POS_END }};
                                    </script>

                                    @push('js')
                                        <script src="/js/admin/js.js"></script>
                                    @endpush

                                    <div class="form-group">
                                        <select name="js-select" id="js-select" class="form-control">
                                            <option value="" id="emp_js_sel" selected disabled>
                                                {{ __('Select value') }}
                                            </option>
                                            @foreach ($js_list as $js_id => $js_item)
                                                <option value="{{ $js_id }}"{{ $js_item['disabled'] ? ' disabled' : '' }}>
                                                    {{ $js_item['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @foreach (\App\Models\Js::$positions as $pos_id => $tags)
                                    <div id="js_position_{{ $pos_id }}">
                                        <h4>{{ $tags[0] }}</h4>
                                        <ul class="list-group page_js_list"
                                            id="page_js_list_{{ $pos_id }}"
                                            data-pos="{{ $pos_id }}" style="min-height: 30px;">
                                        @foreach ($page_js as $t_js)
                                            @if ($t_js->view_pos == $pos_id)
                                            <li class="list-group-item text-left js_item"
                                                data-js="{{ $t_js->js_id }}"
                                                id="js_item_{{ $t_js->js_id }}">
                                                <i class="fa fa-reorder" style="cursor: move"></i> &nbsp;
                                                <span style="float: right">
                                                    <button type="button"
                                                            class="close"
                                                            aria-label="Close"
                                                            data-js="{{ $t_js->js_id }}">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </span>
                                                <a href="/admin/site/js/edit/{{ $t_js->js_id }}" target="_blank">
                                                    {{ $t_js->js->name }}
                                                </a>
                                                <div class="form-group field-pagejs-{{ $t_js->js_id }}-block_pos">
                                                    <input type="hidden" id="pagejs-{{ $t_js->js_id }}-block_pos"
                                                           class="form-control input_block_pos"
                                                           name="PageJs[{{ $t_js->js_id }}][block_pos]"
                                                           value="{{ $t_js->block_pos }}">
                                                </div>
                                                <div class="form-group field-pagejs-{{ $t_js->js_id }}-view_pos">
                                                    <input type="hidden" id="pagejs-{{ $t_js->js_id }}-view_pos"
                                                           class="form-control input_view_pos"
                                                           name="PageJs[{{ $t_js->js_id }}][view_pos]"
                                                           value="{{ $t_js->view_pos }}">
                                                </div>
                                            </li>
                                            @endif
                                        @endforeach
                                        </ul>
                                        <h4>{{ $tags[1] }}</h4>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group text-center form_buttons">
                    <a class="btn btn-round btn-default cancel-button" href="{{ route('admin', [
                        'section' => $section,
                        'model' => $item::getModelNameIdx()
                    ], false) }}">
                        <i class="fa fa-remove"></i> {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="btn btn-round btn-success" name="model_save_action" value="save_{{ $item::getModelNameIdx() }}">
                        <i class="fa fa-save"></i> {{ __('Save') }}
                    </button>
                </div>

            </form>
        </div>
        <div class="col-md-3 col-xs-hidden"></div>
    </div>

    @push('js')
        <script src="/js/admin/page.js"></script>
    @endpush

</x-admin.layout>
