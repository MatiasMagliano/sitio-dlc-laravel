<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'El :attribute debe ser aceptado.',
    'accepted_if' => 'El :attribute debe ser aceptado cuando :other es :value.',
    'active_url' => 'El :attribute no es una URL válida.',
    'after' => 'El :attribute debe ser una fecha posterior a :date.',
    'after_or_equal' => 'El :attribute debe ser posterior o igual a la fecha :date.',
    'alpha' => 'El :attribute debe contener sólo letras.',
    'alpha_dash' => 'El :attribute debe contener sólo letras, números, guiones y guines bajos.',
    'alpha_num' => 'El :attribute debe contener sólo letras y números.',
    'array' => 'El :attribute debe ser un array.',
    'before' => 'El :attribute debe ser una fecha anterior a :date.',
    'before_or_equal' => 'El :attribute debe ser una fecha anterior o igual a :date.',
    'between' => [
        'numeric' => 'El :attribute debe contener entre :min y :max.',
        'file' => 'El :attribute debe contener entre :min y :max kilobytes.',
        'string' => 'El :attribute debe contener entre :min y :max caracteres.',
        'array' => 'El :attribute debe tener entre :min y :max items.',
    ],
    'boolean' => 'El campo :attribute debe ser true o false.',
    'confirmed' => 'Las :attribute no coinciden.',
    'current_password' => 'La contraseña no es correcta.',
    'date' => 'El :attribute no es una fecha válida.',
    'date_equals' => 'El :attribute debe ser una fecha igual a :date.',
    'date_format' => 'El :attribute no coincide con el formato :format.',
    'declined' => 'El :attribute debe ser rechazado.',
    'declined_if' => 'El :attribute debe ser rechazado cuando :other is :value.',
    'different' => 'El :attribute y :other deben ser diferentes.',
    'digits' => 'El :attribute debe tener :digits dígitos.',
    'digits_between' => 'El :attribute debe estar entre :min y :max dígitos.',
    'dimensions' => 'El :attribute tiene dimensiones inválidas.',
    'distinct' => 'El campo :attribute tiene valores duplicados.',
    'email' => 'El :attribute debe ser una dirección de correo electrónico válida.',
    'ends_with' => 'El :attribute debe terminar con alguno de los siguientes valores: :values.',
    'exists' => 'El :attribute seleccionado es inválido.',
    'file' => 'El :attribute debe ser un archivo.',
    'filled' => 'El campo :attribute debe tenr un valor.',
    'gt' => [
        'numeric' => 'El :attribute debe ser mayor a :value.',
        'file' => 'El :attribute debe ser mayor que :value kilobytes.',
        'string' => 'El :attribute debe tener más de :value caracteres.',
        'array' => 'El :attribute debe tener más de :value items.',
    ],
    'gte' => [
        'numeric' => 'El :attribute debe ser mayor o igual a :value.',
        'file' => 'El :attribute debe ser mayor o igual a :value kilobytes.',
        'string' => 'El :attribute debe ser mayor o igual a :value caracteres.',
        'array' => 'El :attribute debe tener :value items o más.',
    ],
    'image' => 'El :attribute debe ser una imagen.',
    'in' => 'El :attribute seleccionado es inválido.',
    'in_array' => 'El campo :attribute no existe en :other.',
    'integer' => 'El :attribute debe ser un entero.',
    'ip' => 'El :attribute debe ser una direccin IP válida.',
    'ipv4' => 'El :attribute debe ser una dirección IPV4 válida.',
    'ipv6' => 'El :attribute debe ser una dirección IPV6 válida.',
    'json' => 'El :attribute debe ser un string JSON válido.',
    'lt' => [
        'numeric' => 'El campo :attribute debe ser menor a :value.',
        'file' => 'El campo :attribute debe ser menor a :value kilobytes.',
        'string' => 'El campo :attribute debe ser menor a :value caracteres.',
        'array' => 'El campo :attribute debe tener menor a :value items.',
    ],
    'lte' => [
        'numeric' => 'El :attribute debe ser menor o igual a :value.',
        'file' => 'El :attribute debe ser menor o igual a :value kilobytes.',
        'string' => 'El :attribute debe ser menor o igual a :value caracteres.',
        'array' => 'El :attribute no debe tener más de :value items.',
    ],
    'max' => [
        'numeric' => 'El campo :attribute no debe ser mayor a :max.',
        'file' => 'El campo :attribute no debe ser mayor a :max kilobytes.',
        'string' => 'El campo :attribute no debe ser mayor a :max caracteres.',
        'array' => 'El campo :attribute no debe tener más de :max items.',
    ],
    'mimes' => 'El :attribute debe ser un archivo del tipo: :values.',
    'mimetypes' => 'El :attribute debe ser un archivo del tipo: :values.',
    'min' => [
        'numeric' => 'El :attribute debe ser al menos de :min.',
        'file' => 'El :attribute debe ser al menos de :min kilobytes.',
        'string' => 'El :attribute debe ser al menos de :min caracteres.',
        'array' => 'El :attribute debe tener al menos de :min items.',
    ],
    'multiple_of' => 'El :attribute debe ser un múltiplo de :value.',
    'not_in' => 'El :attribute seleccionado es inválido.',
    'not_regex' => 'El formato de :attribute es inválido',
    'numeric' => 'El :attribute debe ser un número',
    'password' => 'La contraseña es incorrecta.',
    'present' => 'El campo :attribute debe estar presente.',
    'prohibited' => 'El campo :attribute está prohibido.',
    'prohibited_if' => 'El :attribute está prohibido cuando :other es :value.',
    'prohibited_unless' => 'El :attribute está prohibido a menos que :other es en :values.',
    'prohibits' => 'El campo :attribute prohibe a :other estar presente.',
    'regex' => 'El formato de es inválido.',
    'required' => 'El campo :attribute es requerido',
    'required_if'=> 'El campo :attribute es requerido cuando :other es :value.',
    'required_unless' => 'El campo :attribute es requerido a menos que :other es en :values.',
    'required_with' => 'El campo :attribute es resquerido cuando :values está presente.',
    'required_with_all' => 'El campo :attribute es requerido cuando :values están presentes.',
    'required_without' => 'El campo :attribute es requerido cuando :values no está presente.',
    'required_without_all' => 'El campo :attribute es requerido cuando ninguno de :values están presentes.',
    'same' => 'El :attribute y :other deben coincidir.',
    'size' => [
        'numeric' => 'El :attribute debe ser :size.',
        'file' => 'El :attribute debe tener :size kilobytes.',
        'string' => 'El :attribute debe tener :size caracteres.',
        'array' => 'El :attribute contener :size items.',
    ],
    'starts_with' => 'El :attribute debe comenzar con uno de los siguientes: :values.',
    'string' => 'El :attribute debe ser una cadena de caracteres.',
    'timezone' => 'El :attribute debe ser una zona horaria válida.',
    'unique' => 'El :attribute ya fue tomado.',
    'uploaded' => 'El :attribute falló en subirse.',
    'url' => 'El :attribute debe ser una URL válida.',
    'uuid' => 'El :attribute debe ser un UUID válido.',

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
    | El following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
