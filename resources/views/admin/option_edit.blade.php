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

                @if (!$errors->has('option_key') && isset($item::$default_options[$item->option_key]))
                    <input type="hidden" name="name" value="{{ $item::$default_options[$item->option_key]['name'] }}" />
                    <input type="hidden" name="option_key" value="{{ $item->option_key }}" />
                    <h4>{{ __($item::$default_options[$item->option_key]['name']) }}</h4>
                @else
                    <div class="form-group field-option-name @error('name') has-error @enderror ">
                        <label class="control-label" for="option-name">
                            {{ __('Name') }}
                        </label>
                        <input type="text"
                               id="option-name"
                               class="form-control"
                               name="name"
                               value="{{ $errors->any() ? old('name') : $item->name }}">
                        <div class="help-block">
                            @error('name') {{ $message }} @enderror
                        </div>
                    </div>
                    <div class="form-group field-option-option_key @error('option_key') has-error @enderror ">
                        <label class="control-label" for="option_key">
                            {{ __('Option') }}
                        </label>
                        <input type="text"
                               id="option_key"
                               class="form-control"
                               name="option_key"
                               value="{{ $errors->any() ? old('option_key') : $item->option_key }}">
                        <div class="help-block">
                            @error('option_key') {{ $message }} @enderror
                        </div>
                    </div>
                @endif

                <div class="form-group field-option-option_value @error('option_value') has-error @enderror ">
                    <label class="control-label" for="option_value">
                        {{ __('Option value') }}
                    </label>
                    @if (isset($item::$default_options[$item->option_key]))
                        @if (isset($item::$default_options[$item->option_key]['values']))
                            <select id="option-option_value"
                                    class="form-control"
                                    name="option_value">
                            @foreach ($item::$default_options[$item->option_key]['values'] as $select_key => $select_value)
                                <option value="{{ $select_key }}"
                                    {{ $errors->any() && old('option_value') === $select_key ? ' selected' :
                                       ($item->option_value === $select_key ? ' selected' : '') }}>
                                    {{ __($select_value) }}
                                </option>
                            @endforeach
                            </select>
                        @elseif (isset($item::$default_options[$item->option_key]['relation']))
                            <input type="hidden"
                                   id="relation_field_option_value"
                                   name="option_value"
                                   value="{{ $errors->any() ? old('option_value') : $item->option_value }}">
                            <div class="input-group">
                                <span class="input-group-addon" style="font-size: 22px;">
                                    <i id="relation_field_addonoption_value"
                                       class="fa fa-{{ $item->option_value ? '' : 'un' }}link"></i>
                                </span>
                                <input type="text"
                                       class="form-control relation_field"
                                       data-field="option_value"
                                       data-table="{{ $item->getOptionRelationTable(
                                           $item::$default_options[$item->option_key]['relation']
                                       ) }}"
                                       autocomplete="off"
                                       value="{{ $item->getOptionRelationValue(
                                           $item::$default_options[$item->option_key]['relation']
                                       ) }}">
                            </div>
                        @else
                            <input type="text"
                                   id="option_value"
                                   class="form-control"
                                   name="option_value"
                                   value="{{ $errors->any() ? old('option_value') : $item->option_value }}">
                        @endif

                    @else
                        <input type="text"
                               id="option_value"
                               class="form-control"
                               name="option_value"
                               value="{{ $errors->any() ? old('option_value') : $item->option_value }}">
                    @endif
                    <div class="help-block">
                        @error('option_value') {{ $message }} @enderror
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

</x-admin.layout>
