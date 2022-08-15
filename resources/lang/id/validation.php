<?php

return [
    /*
    |---------------------------------------------------------------------------------------
    | Baris Bahasa untuk Validasi
    |---------------------------------------------------------------------------------------
    |
    | Baris bahasa berikut ini berisi standar pesan kesalahan yang digunakan oleh
    | kelas validasi. Beberapa aturan mempunyai multi versi seperti aturan 'size'.
    | Jangan ragu untuk mengoptimalkan setiap pesan yang ada di sini.
    |
    */
    'invalid_data'         => 'Sepertinya ada data yang tidak valid. Silakan periksa lagi.',
    'accepted'             => 'Kolom isian :attribute harus diterima.',
    'active_url'           => 'Kolom isian :attribute bukan URL yang valid.',
    'after'                => 'Kolom isian :attribute harus tanggal setelah :date.',
    'after_or_equal'       => 'Kolom isian :attribute harus berupa tanggal setelah atau sama dengan tanggal :date.',
    'alpha'                => 'Kolom isian :attribute hanya boleh berisi huruf.',
    'alpha_dash'           => 'Kolom isian :attribute hanya boleh berisi huruf, angka, dan strip.',
    'alpha_num'            => 'Kolom isian :attribute hanya boleh berisi huruf dan angka.',
    'array'                => 'Kolom isian :attribute harus berupa sebuah array.',
    'before'               => 'Kolom isian :attribute harus tanggal sebelum :date.',
    'before_or_equal'      => 'Kolom isian :attribute harus berupa tanggal sebelum atau sama dengan tanggal :date.',
    'between'              => [
        'numeric' => 'Kolom isian :attribute harus antara :min dan :max.',
        'file'    => 'Kolom :attribute harus antara :min dan :max kilobyte.',
        'string'  => 'Kolom isian :attribute harus antara :min dan :max karakter.',
        'array'   => 'Kolom isian :attribute harus antara :min dan :max item.',
    ],
    'boolean'              => 'Kolom isian :attribute harus berupa true atau false',
    'confirmed'            => 'Konfirmasi :attribute tidak cocok.',
    'date'                 => 'Kolom isian :attribute bukan tanggal yang valid.',
    'date_equals'          => 'Kolom :attribute harus berupa tanggal yang sama dengan :date.',
    'date_format'          => 'Kolom isian :attribute tidak cocok dengan format :format.',
    'different'            => 'Kolom isian :attribute dan :other harus berbeda.',
    'digits'               => 'Kolom isian :attribute harus berupa :digits angka.',
    'digits_between'       => 'Kolom isian :attribute harus antara angka :min dan :max.',
    'dimensions'           => 'Kolom :attribute tidak memiliki dimensi gambar yang valid.',
    'distinct'             => 'Kolom isian :attribute memiliki nilai yang duplikat.',
    'email'                => 'Kolom isian :attribute harus berupa alamat surel yang valid.',
    'exists'               => 'Kolom isian :attribute yang dipilih tidak valid.',
    'file'                 => ':attribute harus berupa sebuah berkas.',
    'filled'               => 'Kolom isian :attribute harus memiliki nilai.',
    'gt'                   => [
        'numeric' => 'Kolom isian :attribute harus lebih besar dari :value.',
        'file'    => 'Kolom :attribute harus lebih besar dari :value kilobyte.',
        'string'  => 'Kolom isian :attribute harus lebih besar dari :value karakter.',
        'array'   => 'Kolom isian :attribute harus lebih dari :value item.',
    ],
    'gte'                  => [
        'numeric' => 'Kolom isian :attribute harus lebih besar dari atau sama dengan :value.',
        'file'    => 'Kolom :attribute harus lebih besar dari atau sama dengan :value kilobyte.',
        'string'  => 'Kolom isian :attribute harus lebih besar dari atau sama dengan :value karakter.',
        'array'   => 'Kolom isian :attribute harus mempunyai :value item atau lebih.',
    ],
    'image'                => 'Kolom isian :attribute harus berupa gambar.',
    'in'                   => 'Kolom isian :attribute yang dipilih tidak valid.',
    'in_array'             => 'Kolom isian :attribute tidak terdapat dalam :other.',
    'integer'              => 'Kolom isian :attribute harus merupakan bilangan bulat.',
    'ip'                   => 'Kolom isian :attribute harus berupa alamat IP yang valid.',
    'ipv4'                 => 'Kolom isian :attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'                 => 'Kolom isian :attribute harus berupa alamat IPv6 yang valid.',
    'json'                 => 'Kolom isian :attribute harus berupa JSON string yang valid.',
    'lt'                   => [
        'numeric' => 'Kolom isian :attribute harus kurang dari :value.',
        'file'    => 'Kolom :attribute harus kurang dari :value kilobyte.',
        'string'  => 'Kolom isian :attribute harus kurang dari :value karakter.',
        'array'   => 'Kolom isian :attribute harus kurang dari :value item.',
    ],
    'lte'                  => [
        'numeric' => 'Kolom isian :attribute harus kurang dari atau sama dengan :value.',
        'file'    => 'Kolom :attribute harus kurang dari atau sama dengan :value kilobyte.',
        'string'  => 'Kolom isian :attribute harus kurang dari atau sama dengan :value karakter.',
        'array'   => 'Kolom isian :attribute harus tidak lebih dari :value item.',
    ],
    'max'                  => [
        'numeric' => 'Kolom isian :attribute seharusnya tidak lebih dari :max.',
        'file'    => 'Kolom :attribute seharusnya tidak lebih dari :max kilobyte.',
        'string'  => 'Kolom isian :attribute seharusnya tidak lebih dari :max karakter.',
        'array'   => 'Kolom isian :attribute seharusnya tidak lebih dari :max item.',
    ],
    'mimes'                => 'Kolom isian :attribute harus dokumen berjenis : :values.',
    'mimetypes'            => 'Kolom isian :attribute harus dokumen berjenis : :values.',
    'min'                  => [
        'numeric' => 'Kolom isian :attribute harus minimal :min.',
        'file'    => 'Kolom :attribute harus minimal :min kilobyte.',
        'string'  => 'Kolom isian :attribute harus minimal :min karakter.',
        'array'   => 'Kolom isian :attribute harus minimal :min item.',
    ],
    'not_in'               => 'Kolom isian :attribute yang dipilih tidak valid.',
    'not_regex'            => 'Format Kolom isian :attribute tidak valid.',
    'numeric'              => 'Kolom isian :attribute harus berupa angka.',
    'present'              => 'Kolom isian :attribute wajib ada.',
    'regex'                => 'Format Kolom isian :attribute tidak valid.',
    'required'             => ':attribute wajib diisi.',
    'required_if'          => 'Kolom isian :attribute wajib diisi bila :other adalah :value.',
    'required_unless'      => 'Kolom isian :attribute wajib diisi kecuali :other memiliki nilai :values.',
    'required_with'        => 'Kolom isian :attribute wajib diisi bila terdapat :values.',
    'required_with_all'    => 'Kolom isian :attribute wajib diisi bila terdapat :values.',
    'required_without'     => 'Kolom isian :attribute wajib diisi bila tidak terdapat :values.',
    'required_without_all' => 'Kolom isian :attribute wajib diisi bila tidak terdapat ada :values.',
    'same'                 => 'Kolom isian :attribute dan :other harus sama.',
    'size'                 => [
        'numeric' => 'Kolom isian :attribute harus berukuran :size.',
        'file'    => 'Kolom :attribute harus berukuran :size kilobyte.',
        'string'  => 'Kolom isian :attribute harus berukuran :size karakter.',
        'array'   => 'Kolom isian :attribute harus mengandung :size item.',
    ],
    'starts_with'          => 'Kolom :attribute harus dimulai dengan salah satu dari berikut ini: :values',
    'string'               => 'Kolom isian :attribute harus berupa string.',
    'timezone'             => 'Kolom isian :attribute harus berupa zona waktu yang valid.',
    'unique'               => 'Kolom isian :attribute sudah ada sebelumnya.',
    'uploaded'             => 'Kolom isian :attribute gagal diunggah.',
    'url'                  => 'Format Kolom isian :attribute tidak valid.',
    'uuid'                 => 'Kolom :attribute harus UUID yang valid.',
    'phone' => 'Nomor telepon tidak valid',
    'another'=>'Silahkan menggunakan :attribute yang lain',

    /*
    |---------------------------------------------------------------------------------------
    | Baris Bahasa untuk Validasi Kustom
    |---------------------------------------------------------------------------------------
    |
    | Di sini Anda dapat menentukan pesan validasi kustom untuk atribut dengan menggunakan
    | konvensi "attribute.rule" dalam penamaan baris. Hal ini membuat cepat dalam
    | menentukan spesifik baris bahasa kustom untuk aturan atribut yang diberikan.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |---------------------------------------------------------------------------------------
    | Kustom Validasi Atribut
    |---------------------------------------------------------------------------------------
    |
    | Baris bahasa berikut digunakan untuk menukar atribut 'place-holders'
    | dengan sesuatu yang lebih bersahabat dengan pembaca seperti Alamat Surel daripada
    | "surel" saja. Ini benar-benar membantu kita membuat pesan sedikit bersih.
    |
    */

    'attributes' => [
    ],
];
