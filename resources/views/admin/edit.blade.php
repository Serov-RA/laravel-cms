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
                @foreach ($fields as $field_key => $field_data)
                <div class="form-group field-{{ $item::getModelNameIdx() }}-{{ $field_key }} @error($field_key) has-error @enderror ">
                    <label class="control-label" for="{{ $item::getModelNameIdx() }}-{{ $field_key }}">
                        {{ $field_data['name'] }}
                    </label>
                    @if ($field_data['select'])
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
                                   name="{{ $field_key }}_text_value"
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

</x-admin.layout>
