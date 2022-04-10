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

        var dropzoneSuccessEval = function(file) {
            if (file.xhr.status != 200) {
                uploadError('Error loading file:' + file.xhr.status);
                return;
            }

            var response = JSON.parse(file.xhr.response);

            if (!response.status || response.status != 'ok') {
                uploadError('Error:' + (response.message ? response.message : response));
                return;
            }

            $('#css-path').val(response.message);
            uploadSuccess();
        }

        var dropzoneErrorEval = function(file) {
            uploadError('Error loading file')
        }

        function uploadError(message) {
            $('.field-css-path').addClass('has-error');
            $('.field-css-path .help-block').html(message);
        }

        function uploadSuccess() {
            $('.field-css-path').removeClass('has-error');
            $('.field-css-path .help-block').html('');
        }
    </script>

    <div class="row">
        <div class="col-md-3 col-xs-hidden"></div>
        <div class="col-md-6 col-xs-12">
            <form method="post">
                @csrf

                <div class="form-group field-css-name @error('name') has-error @enderror ">
                    <label class="control-label" for="css-name">
                        {{ __('Name') }}
                    </label>
                    <input type="text"
                           id="css-name"
                           class="form-control"
                           name="name"
                           value="{{ $errors->any() ? old('name') : $item->name }}">
                    <div class="help-block">
                        @error('name') {{ $message }} @enderror
                    </div>
                </div>

                <div class="form-group field-css-type @error('type') has-error @enderror ">
                    <label class="control-label" for="css-type">
                        {{ __('Type') }}
                    </label>
                    <select id="css-type"
                            class="form-control"
                            name="type">
                        @foreach ($item->typeSelect() as $select_key => $select_value)
                            <option value="{{ $select_key }}"
                                {{ $errors->any() && old('type') == $select_key ? ' selected' :
                                   ($item->type == $select_key ? ' selected' : '') }}>
                                {{ $select_value }}
                            </option>
                        @endforeach
                    </select>
                    <div class="help-block">
                        @error('type') {{ $message }} @enderror
                    </div>
                </div>
                <div class="form-group field-css-path @error('path') has-error @enderror ">
                    <label class="control-label" for="css-path">
                        {{ __('Path') }}
                    </label>
                    <div class="input-group">
                        <input type="text"
                               id="css-path"
                               class="form-control"
                               name="path"
                               aria-describedby="upload-addon"
                               value="{{ $errors->any() ? old('path') : $item->path }}">
                        <span class="input-group-addon" id="upload-addon" style="font-size: 22px; cursor: pointer;">
                            <i class="fa fa-upload dropzone_upload"
                               data-options='{{ json_encode([
                                   'url' => route('admin', [
                                       'section' => $section,
                                       'model' => $item::getModelNameIdx(),
                                       'action' => 'upload'
                                    ], false).'?_token='.csrf_token().'&id='.$item->id,
                                    'createImageThumbnails' => false,
                                    'disablePreviews' => true,
                               ]) }}'></i>
                        </span>
                        @once
                            @push('js')
                                <script src="/js/admin/dropzone.min.js"></script>
                            @endpush
                        @endonce
                    </div>
                    <div class="help-block">
                        @error('path') {{ $message }} @enderror
                    </div>
                </div>

                <div class="form-group field-css-content @error('content') has-error @enderror ">
                    <label class="control-label" for="css-content">
                        {{ __('Content') }}
                    </label>
                    <textarea name="content"
                              id="css-content"
                              class="code_redactor"
                    >{{ $errors->any() ? old('content') : $item->content }}</textarea>
                    @once
                        @push('js')
                            <script src="/lib/codemirror/lib/codemirror.js"></script>
                        @endpush
                        @push('css')
                            <link href="/lib/codemirror/lib/codemirror.css" rel="stylesheet">
                        @endpush
                    @endonce
                    <div class="help-block">
                        @error('content') {{ $message }} @enderror
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
        <script type="text/javascript">
            function typeSelect() {
                var type = $('#css-type').val();

                if (type == '0') {
                    $('.field-css-path').hide();
                    $('.field-css-content').show();
                } else {
                    $('.field-css-path').show();
                    $('.field-css-content').hide();
                }
            }

            $(document).ready(function(){
                typeSelect();

                $('#css-type').on('change', typeSelect);
            });
        </script>
    @endpush

</x-admin.layout>
