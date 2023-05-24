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

    'accepted' => 'Laukam :attribute ir jābūt apstiprinātam.',
    'accepted_if' => 'Laukam :attribute ir jābūt apstiprinātam, ja :other ir :value.',
    'active_url' => 'Laukam :attribute ir jābūt derīgai URL.',
    'after' => 'Laukam :attribute ir jābūt datumam pēc :date.',
    'after_or_equal' => 'Laukam :attribute ir jābūt datumam pēc vai vienādam ar :date.',
    'alpha' => 'Lauks :attribute drīkst saturēt tikai burtus.',
    'alpha_dash' => 'Lauks :attribute drīkst saturēt tikai burtus, ciparus, domuzīmes un pasvītras.',
    'alpha_num' => 'Lauks :attribute drīkst saturēt tikai burtus un ciparus.',
    'array' => 'Laukam :attribute ir jābūt masīvam.',
    'ascii' => 'Laukam :attribute ir jāsatur tikai viena baita alfa-numeric simbolus un simbolus.',
    'before' => 'Laukam :attribute ir jābūt datumam pirms :date.',
    'before_or_equal' => 'Laukam :attribute ir jābūt datumam pirms vai vienādam ar :date.',
    'between' => [
        'array' => 'Laukam :attribute ir jābūt starp :min un :max vienumiem.',
        'file' => 'Laukam :attribute ir jābūt starp :min un :max kilobaitiem.',
        'numeric' => 'Laukam :attribute ir jābūt starp :min un :max.',
        'string' => 'Laukam :attribute ir jābūt starp :min un :max rakstzīmēm.',
    ],
    'boolean' => 'Laukam :attribute ir jābūt patiesam vai nepatiesam.',
    'confirmed' => 'Lauka :attribute apstiprinājums nesakrīt.',
    'current_password' => 'Nepareiza parole.',
    'date' => 'Laukam :attribute ir jābūt derīgam datumam.',
    'date_equals' => 'Laukam :attribute ir jābūt datumam vienādam ar :date.',
    'date_format' => 'Lauks :attribute jābūt formātā :format.',
    'decimal' => 'Laukam :attribute ir jābūt :decimal ciparu aiz komata.',
    'declined' => 'Laukam :attribute ir jābūt noraidītam.',
    'declined_if' => 'Laukam :attribute ir jābūt noraidītam, ja :other ir :value.',
    'different' => 'Laukam :attribute un :other ir jābūt atšķirīgiem.',
    'digits' => 'Laukam :attribute ir jābūt :digits ciparam.',
    'digits_between' => 'Laukam :attribute ir jābūt starp :min un :max cipariem.',
    'dimensions' => 'Laukam :attribute ir nederīgi attēla izmēri.',
    'distinct' => 'Laukam :attribute ir dublēta vērtība.',
    'doesnt_end_with' => 'Laukam :attribute nedrīkst beigties ar vienu no šādiem: :values.',
    'doesnt_start_with' => 'Laukam :attribute nedrīkst sākties ar vienu no šādiem: :values.',
    'email' => 'Laukam :attribute ir jābūt derīgai e-pasta adresei.',
    'ends_with' => 'Laukam :attribute ir jābeidzas ar vienu no šādiem: :values.',
    'enum' => 'Izvēlētā :attribute ir nederīga.',
    'exists' => 'Izvēlētais :attribute ir nederīgs.',
    'file' => 'Laukam :attribute ir jābūt failam.',
    'filled' => 'Laukam :attribute ir jābūt aizpildītam.',
    'gt' => [
        'array' => 'Laukam :attribute ir jābūt vairāk nekā :value vienībām.',
        'file' => 'Laukam :attribute ir jābūt lielākam par :value kilobaitiem.',
        'numeric' => 'Laukam :attribute ir jābūt lielākam par :value.',
        'string' => 'Laukam :attribute ir jābūt lielākam par :value rakstzīmēm.',
    ],
    'gte' => [
        'array' => 'Laukam :attribute ir jābūt vismaz :value vienībām.',
        'file' => 'Laukam :attribute ir jābūt lielākam vai vienādam ar :value kilobaitiem.',
        'numeric' => 'Laukam :attribute ir jābūt lielākam vai vienādam ar :value.',
        'string' => 'Laukam :attribute ir jābūt lielākam vai vienādam ar :value rakstzīmēm.',
    ],
    'image' => 'Laukam :attribute ir jābūt attēlam.',
    'in' => 'Izvēlētais :attribute ir nederīgs.',
    'in_array' => 'Laukam :attribute jāeksistē :other.',
    'integer' => 'Laukam :attribute ir jābūt veselam skaitlim.',
    'ip' => 'Laukam :attribute ir jābūt derīgai IP adresē.',
    'ipv4' => 'Laukam :attribute ir jābūt derīgai IPv4 adresē.',
    'ipv6' => 'Laukam :attribute ir jābūt derīgai IPv6 adresē.',
    'json' => 'Laukam :attribute ir jābūt derīgai JSON virknei.',
    'lowercase' => 'Laukam :attribute ir jābūt mazajiem burtiem.',
    'lt' => [
        'array' => 'Laukam :attribute ir jābūt mazāk nekā :value vienībām.',
        'file' => 'Laukam :attribute ir jābūt mazākam par :value kilobaitiem.',
        'numeric' => 'Laukam :attribute ir jābūt mazākam par :value.',
        'string' => 'Laukam :attribute ir jābūt mazākam par :value rakstzīmēm.',
    ],
    'lte' => [
        'array' => 'Laukam :attribute nedrīkst būt vairāk kā :value vienības.',
        'file' => 'Laukam :attribute ir jābūt mazākam vai vienādam ar :value kilobaitiem.',
        'numeric' => 'Laukam :attribute ir jābūt mazākam vai vienādam ar :value.',
        'string' => 'Laukam :attribute ir jābūt mazākam vai vienādam ar :value rakstzīmēm.',
    ],
    'mac_address' => 'Laukam :attribute ir jābūt derīgai MAC adresē.',
    'max' => [
        'array' => 'Laukam :attribute nedrīkst būt vairāk kā :max vienības.',
        'file' => 'Laukam :attribute nedrīkst būt lielākam par :max kilobaitiem.',
        'numeric' => 'Laukam :attribute nedrīkst būt lielākam par :max.',
        'string' => 'Laukam :attribute nedrīkst būt lielākam par :max rakstzīmēm.',
    ],
    'max_digits' => 'Laukam :attribute nedrīkst būt vairāk kā :max cipari.',
    'mimes' => 'Laukam :attribute ir jābūt failam ar tipu: :values.',
    'mimetypes' => 'Laukam :attribute ir jābūt failam ar tipu: :values.',
    'min' => [
        'array' => 'Laukam :attribute ir jābūt vismaz :min vienībām.',
        'file' => 'Laukam :attribute ir jābūt vismaz :min kilobaitiem.',
        'numeric' => 'Laukam :attribute ir jābūt vismaz :min.',
        'string' => 'Laukam :attribute ir jābūt vismaz :min rakstzīmēm.',
    ],
    'min_digits' => 'Laukam :attribute ir jābūt vismaz :min cipariem.',
    'missing' => 'Laukam :attribute ir jābūt pazudis.',
    'missing_if' => 'Laukam :attribute ir jābūt pazudis, kad :other ir :value.',
    'missing_unless' => 'Laukam :attribute ir jābūt pazudis, ja :other nav :value.',
    'missing_with' => 'Laukam :attribute ir jābūt pazudis, kad ir klāt :values.',
    'missing_with_all' => 'Laukam :attribute ir jābūt pazudis, kad ir klāt viss no :values.',
    'missing_without' => 'Laukam :attribute ir jābūt pazudis, kad nav klāt :values.',
    'missing_without_all' => 'Laukam :attribute ir jābūt pazudis, kad nav klāt neviens no :values.',
    'not_in' => 'Izvēlētais :attribute ir nederīgs.',
    'not_regex' => 'Laukam :attribute ir nederīgs formāts.',
    'numeric' => 'Laukam :attribute ir jābūt skaitlim.',
    'password' => 'Parole ir nepareiza.',
    'present' => 'Laukam :attribute ir jābūt klāt.',
    'regex' => 'Laukam :attribute ir nederīgs formāts.',
    'required' => 'Lauks :attribute ir obligāts.',
    'required_if' => 'Lauks :attribute ir obligāts, ja :other ir :value.',
    'required_unless' => 'Lauks :attribute ir obligāts, ja :other nav :values.',
    'required_with' => 'Lauks :attribute ir obligāts, kad ir klāt :values.',
    'required_with_all' => 'Lauks :attribute ir obligāts, kad ir klāt visi :values.',
    'required_without' => 'Lauks :attribute ir obligāts, kad nav klāt :values.',
    'required_without_all' => 'Lauks :attribute ir obligāts, kad nav klāt neviens no :values.',
    'same' => 'Laukam :attribute un :other ir jāsakrīt.',
    'size' => [
        'array' => 'Laukam :attribute ir jābūt :size vienībām.',
        'file' => 'Laukam :attribute ir jābūt :size kilobaitiem.',
        'numeric' => 'Laukam :attribute ir jābūt :size.',
        'string' => 'Laukam :attribute ir jābūt :size rakstzīmēm.',
    ],
    'starts_with' => 'Laukam :attribute ir jāsākas ar vienu no šādiem: :values.',
    'string' => 'Laukam :attribute ir jābūt virknei.',
    'timezone' => 'Laukam :attribute ir jābūt derīgai laika zonai.',
    'unique' => 'Laukam :attribute jau ir aizņemts.',
    'uploaded' => 'Lauka :attribute augšupielāde neizdevās.',
    'url' => 'Laukam :attribute ir nederīgs formāts.',
    'uuid' => 'Laukam :attribute ir jābūt derīgam UUID identifikatoram.',

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

    'attributes' => [
        'username' => 'lietotājvārds',
        'first_name' => 'vārds',
        'last_name' => 'uzvārds',
        'password' => 'parole',
        'email' => 'e-pasts',
        'image' => 'profila bilde'
    ],

];
