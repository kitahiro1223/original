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

    'accepted' => ':attribute は受け入れられる必要があります。',
    'accepted_if' => ':other が :value の場合、:attribute を受け入れる必要があります。',
    'active_url' => ':attribute は有効な URL ではありません。',
    'after' => ':attribute は :date より後の日付でなければなりません。',
    'after_or_equal' => ':attribute は :date 以降の日付でなければなりません。',
    'alpha' => ':attribute には文字のみを含める必要があります。',
    'alpha_dash' => ':attribute には、文字、数字、ダッシュ、およびアンダースコアのみを含める必要があります。',
    'alpha_num' => ':attribute には文字と数字のみを含める必要があります。',
    'array' => ':attribute は配列でなければなりません。',
    'ascii' => ':attribute には、半角英数字と記号のみを含める必要があります。',
    'before' => ':attribute は :date より前の日付でなければなりません。',
    'before_or_equal' => ':attribute は :date より前または等しい日付でなければなりません。',
    'between' => [
        'array' => ':attribute には :min から :max のアイテムが必要です。',
        'file' => ':attribute は :min から :max キロバイトの間でなければなりません.',
        'numeric' => ':attribute は :min と :max の間でなければなりません。',
        'string' => ':attribute は :min から :max 文字の間でなければなりません.',
    ],
    'boolean' => ':attribute フィールドは true または false でなければなりません。',
    'confirmed' => ':attribute の確認が一致しません。',
    'current_password' => 'パスワードが正しくありません。',
    'date' => ':attribute は有効な日付ではありません。',
    'date_equals' => ':attribute は :date と等しい日付でなければなりません。',
    'date_format' => ':attribute がフォーマット :format と一致しません。',
    'decimal' => ':attribute には :decimal の小数点以下の桁数が必要です。',
    'declined' => ':attribute は辞退する必要があります。',
    'declined_if' => ':other が :value の場合、:attribute を拒否する必要があります。',
    'different' => ':attribute と :other は異なる必要があります。',
    'digits' => ':attribute は :digits 桁でなければなりません。',
    'digits_between' => ':attribute は :min から :max 桁の間でなければなりません.',
    'dimensions' => ':attribute の画像サイズが無効です。',
    'distinct' => ':attribute フィールドの値が重複しています。',
    'doesnt_end_with' => ':attribute は、次のいずれかで終了することはできません: :values.',
    'doesnt_start_with' => ':attribute は、次のいずれかで開始することはできません: :values.',
    'email' => ':attribute は有効なメールアドレスでなければなりません.',
    'ends_with' => ':attribute は次のいずれかで終わる必要があります: :values.',
    'enum' => '選択された :attribute は無効です。',
    'exists' => '選択された :attribute は無効です。',
    'file' => ':attribute はファイルでなければなりません。',
    'filled' => ':attribute フィールドには値が必要です。',
    'gt' => [
        'array' => ':attribute には :value 以上の項目が必要です。',
        'file' => ':attribute は :value キロバイトより大きくなければなりません.',
        'numeric' => ':attribute は :value より大きくなければなりません。',
        'string' => ':attribute は :value 文字より大きくなければなりません。',
    ],
    'gte' => [
        'array' => ':attribute には :value 個以上の項目が必要です。',
        'file' => ':attribute は :value キロバイト以上である必要があります。',
        'numeric' => ':attribute は :value 以上でなければなりません。',
        'string' => ':attribute は :value 文字以上である必要があります。',
    ],
    'image' => ':attribute は画像でなければなりません。',
    'in' => '選択された :attribute は無効です。',
    'in_array' => ':attribute フィールドは :other に存在しません。',
    'integer' => ':attribute は整数でなければなりません。',
    'ip' => ':attribute は有効な IP アドレスでなければなりません。',
    'ipv4' => ':attribute は有効な IPv4 アドレスでなければなりません。',
    'ipv6' => ':attribute は有効な IPv6 アドレスでなければなりません。',
    'json' => ':attribute は有効な JSON 文字列でなければなりません。',
    'lowercase' => ':attribute は小文字でなければなりません。',
    'lt' => [
        'array' => ':attribute の項目数は :value 未満である必要があります。',
        'file' => ':attribute は :value キロバイト未満でなければなりません。',
        'numeric' => ':attribute は :value 未満でなければなりません。',
        'string' => ':attribute は :value 文字未満でなければなりません。',
    ],
    'lte' => [
        'array' => ':attribute には :value を超えるアイテムを含めることはできません。',
        'file' => ':attribute は :value キロバイト以下でなければなりません.',
        'numeric' => ':attribute は :value 以下でなければなりません。',
        'string' => ':attribute は :value 文字以下でなければなりません。',
    ],
    'mac_address' => ':attribute は有効な MAC アドレスでなければなりません。',
    'max' => [
        'array' => ':attribute には :max 個を超えるアイテムを含めることはできません。',
        'file' => ':attribute は :max キロバイトを超えてはなりません.',
        'numeric' => ':attribute は :max を超えてはなりません',
        'string' => ':attribute は :max 文字を超えてはなりません.',
    ],
    'max_digits' => ':attribute は :max 桁を超えてはなりません.',
    'mimes' => ':attribute は、タイプ: :values のファイルでなければなりません。',
    'mimetypes' => ':attribute はタイプ: :values のファイルでなければなりません。',
    'min' => [
        'array' => ':attribute には少なくとも :min 個のアイテムが必要です。',
        'file' => ':attribute は少なくとも :min キロバイトでなければなりません。',
        'numeric' => ':attribute は :min 以上でなければなりません。',
        'string' => ':attribute は少なくとも :min 文字でなければなりません.',
    ],
    'min_digits' => ':attribute には少なくとも :min 桁が必要です。',
    'multiple_of' => ':attribute は :value の倍数でなければなりません。',
    'not_in' => '選択された:attributeは無効です.',
    'not_regex' => ':attribute 形式が無効です。',
    'numeric' => ':attribute は数値でなければなりません。',
    'password' => [
        'letters' => ':attribute には、少なくとも 1 つの文字が含まれている必要があります。',
        'mixed' => ':attribute には、少なくとも 1 つの大文字と 1 つの小文字を含める必要があります。',
        'numbers' => ':attribute には、少なくとも 1 つの数字を含める必要があります。',
        'symbols' => ':attribute には、少なくとも 1 つのシンボルが含まれている必要があります。',
        'uncompromised' => '指定された :attribute がデータ リークに含まれています。 別の属性を選択してください。',
    ],
    'present' => ':attribute フィールドが存在する必要があります。',
    'prohibited' => ':attribute フィールドは禁止されています。',
    'prohibited_if' => ':other が :value の場合、:attribute フィールドは禁止されています。',
    'prohibited_unless' => ':values に :other がない限り、:attribute フィールドは禁止されています。',
    'prohibits' => ':attribute フィールドは、:other の存在を禁止します。',
    'regex' => ':attribute 形式が無効です。',
    'required' => ':attribute フィールドは必須です。',
    'required_array_keys' => ':attribute フィールドには、:values のエントリが含まれている必要があります。',
    'required_if' => ':other が :value の場合、:attribute フィールドは必須です。',
    'required_if_accepted' => ':other が受け入れられる場合、:attribute フィールドは必須です。',
    'required_unless' => ':values に :other がない限り、:attribute フィールドは必須です。',
    'required_with' => ':values が存在する場合、:attribute フィールドは必須です。',
    'required_with_all' => ':values が存在する場合、:attribute フィールドは必須です。',
    'required_without' => ':values が存在しない場合、:attribute フィールドは必須です。',
    'required_without_all' => ':values が存在しない場合、:attribute フィールドは必須です。',
    'same' => ':attribute と :other は一致する必要があります。',
    'size' => [
        'array' => ':attribute には :size 項目を含める必要があります。',
        'file' => ':attribute は :size キロバイトでなければなりません。',
        'numeric' => ':attribute は :size でなければなりません。',
        'string' => ':attribute は :size 文字でなければなりません。',
    ],
    'starts_with' => ':attribute は次のいずれかで始まる必要があります: :values.',
    'string' => ':attribute は文字列でなければなりません。',
    'timezone' => ':attribute は有効なタイムゾーンでなければなりません。',
    'unique' => ':attribute は既に取得されています。',
    'uploaded' => ':attribute のアップロードに失敗しました。',
    'uppercase' => ':attribute は大文字でなければなりません。',
    'url' => ':attribute は有効な URL でなければなりません。',
    'ulid' => ':attribute は有効な ULID でなければなりません。',
    'uuid' => ':attribute は有効な UUID でなければなりません。',

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
