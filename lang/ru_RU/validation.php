<?php

return [
    'accepted' => 'Значение должно быть принято.',
    'accepted_if' => 'Значение должно быть принято, если :other равно :value.',
    'active_url' => 'Значение не является допустимым URL.',
    'after' => 'Значение должно быть датой после :date.',
    'after_or_equal' => 'Значение должно быть датой после :date или равным ей.',
    'alpha' => 'Значение должно содержать только буквы.',
    'alpha_dash' => 'Значение должно содержать только буквы, цифры, дефисы и символы подчеркивания.',
    'alpha_num' => 'Значение должно содержать только буквы и цифры.',
    'array' => 'Значение должно быть массивом.',
    'before' => 'Значение должно быть датой до :date.',
    'before_or_equal' => 'Значение должно быть датой, предшествующей :date или равной ей.',
    'between' => [
        'numeric' => 'Значение должно быть между :min и :max.',
        'file' => 'Значение должно быть между :min и :max килобайтами.',
        'string' => 'Значение должно быть между символами :min и :max.',
        'array' => 'Значение должно содержать от :min до :max элементов.',
    ],
    'boolean' => 'Значение должно быть истинным или ложным.',
    'confirmed' => 'Подтверждение не соответствует.',
    'current_password' => 'Неверный пароль.',
    'date' => 'Значение не является действительной датой.',
    'date_equals' => 'Значение должно быть датой, равной :date.',
    'date_format' => 'Значение не соответствует формату :format.',
    'declined' => 'Значение должно быть отклонено.',
    'declined_if' => 'Значение должен быть отклонено, если :other равно :value.',
    'other' => 'Текущее поле и :other должны быть разными.',
    'digits' => 'Значение должно быть :digits digits.',
    'digits_between' => 'Значение должно быть между цифрами :min и :max.',
    'dimensions' => 'Значение имеет недопустимые размеры изображения.',
    'distinct' => 'Значение имеет повторяющееся значение.',
    'email' => 'Значение должно быть действительным адресом электронной почты.',
    'ends_with' => 'Значение должно заканчиваться одним из следующих значкений: :values.',
    'enum' => 'Выбранное значение недействительно.',
    'exists' => 'Выбранное значение недействительно.',
    'file' => 'Значение должно быть файлом.',
    'filled' => 'Поле должно иметь значение.',
    'gt' => [
        'numeric' => 'Значение должно быть больше :value.',
        'file' => 'Значение должно быть больше :value килобайт.',
        'string' => 'Значение должно быть больше символов :value.',
        'array' => 'Значение должно содержать больше элементов, чем :value.',
    ],
    'gte' => [
        'numeric' => 'Значение должно быть больше или равен :value.',
        'file' => 'Значение должно быть больше или равен :value килобайтам.',
        'string' => 'Значение должно быть больше или равен символам :value.',
        'array' => 'Значение должно содержать элементы :value или более.',
    ],
    'image' => 'Значение должно быть изображением.',
    'in' => 'Выбранное значение недействительно.',
    'in_array' => 'Значение не существует в :other.',
    'integer' => 'Значение должно быть целым числом.',
    'ip' => 'Значение должно быть действительным IP-адресом.',
    'ipv4' => 'Значение должно быть действительным адресом IPv4.',
    'ipv6' => 'Значение должно быть действительным адресом IPv6.',
    'json' => 'Значение должно быть допустимой строкой JSON.',
    'lt' => [
        'numeric' => 'Значение должно быть меньше :value.',
        'file' => 'Значение должно быть меньше :value килобайт.',
        'string' => 'Значение должно быть меньше символов :value.',
        'array' => 'Значение должно содержать элементов меньше :value.',
    ],
    'lte' => [
        'numeric' => 'Значение должно быть меньше или равен :value.',
        'file' => 'Значение должно быть меньше или равен :value килобайтам.',
        'string' => 'Значение должно быть меньше или равен символам :value.',
        'array' => 'Значение не должно содержать больше элементов, чем :value.',
    ],
    'mac_address' => 'Значение должно быть действительным MAC-адресом.',
    'max' => [
        'numeric' => 'Значение не должно быть больше :max.',
        'file' => 'Значение не должно превышать :max килобайт.',
        'string' => 'Значение не должно превышать :max символов.',
        'array' => 'Значение не должно содержать не более :max элементов.',
    ],
    'mimes' => 'Значение должно быть файлом типа: :values.',
    'mimetypes' => 'Значение должно быть файлом типа: :values.',
    'min' => [
        'numeric' => 'Значение должно быть не меньше :min.',
        'file' => 'Значение должно быть не менее :min килобайт.',
        'string' => 'Значение должно содержать не менее :min символов.',
        'array' => 'Значение должно содержать не менее :min элементов.',
    ],
    'multiple_of' => 'Значение должно быть кратен :value.',
    'not_in' => 'Выбранное значение недействительно.',
    'not_regex' => 'Неверный формат.',
    'numeric' => 'Значение должно быть числом.',
    'password' => 'Неверный пароль.',
    'present' => 'Значение должно присутствовать.',
    'prohibited' => 'Значение запрещено.',
    'prohibited_if' => 'Значение запрещено, если :other равно :value.',
    'prohibited_unless' => 'Значение запрещено, если :other не находится в :values.',
    'prohibits' => 'Значение запрещает присутствие :other.',
    'regex' => 'Неверный формат.',
    'required' => 'Поле обязательно для заполнения',
    'required_array_keys' => 'Значение должно содержать записи для: :values.',
    'required_if' => 'Значение обязательно, если :other равно :value.',
    'required_unless' => 'Значение является обязательным, если только :other не находится в :values.',
    'required_with' => 'Значение обязательно, если присутствует :values.',
    'required_with_all' => 'Значение обязательно, если присутствуют :values.',
    'required_without' => 'Значение является обязательным, если :values отсутствует.',
    'required_without_all' => 'Значение является обязательным, если ни одно из :value не присутствует.',
    'same' => 'Значение поля и :other должны совпадать.',
    'size' => [
        'numeric' => 'Значение должно быть :size.',
        'file' => 'Значение должно быть :size килобайт.',
        'string' => 'Значение должно быть размером :size символов.',
        'array' => 'Значение должно содержать элементы :size.',
    ],
    'starts_with' => 'Значение должно начинаться с одного из следующих: :values.',
    'string' => 'Значение должно быть строкой.',
    'timezone' => 'Значение должно быть действительным часовым поясом.',
    'unique' => 'Значение не уникально',
    'uploaded' => 'Не удалось загрузить файл',
    'url' => 'Значение должно быть допустимым URL.',
    'uuid' => 'Значение должно быть действительным UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
