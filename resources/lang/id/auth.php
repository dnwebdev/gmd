<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'Email atau password yang Anda masukkan salah.',
    'throttle' => 'Terlalu banyak login gagal. Silakan coba lagi dalam :seconds detik.',
    'not_active'=>'Akun Anda belum aktif. Silahkan aktifkan akun Anda terlebih dahulu',
    'already_active'=>'Akun Anda telah terdaftar dan sudah diaktifkan',
    'suspend'=>'Akun Anda ditangguhkan',
    'incorrect'=>'Email atau Password Anda salah',
    'email' => 'Email Anda Salah',
    'telp' => 'Nomor Telepon Anda Salah',

    'validation' => [
        'telp' => [
            'required' => 'Kolom isian Nomor Telepon wajib diisi',
            'number' => 'Kolom isian Nomor Telepon harus berupa angka.',
//            'minlength' => 'Kolom isian Nomor Telepon harus minimal 9.',
            'forgot_password' => 'Nomor telepon seluler tidak terdaftar'
        ],
        'email' => [
            'required' => 'Kolom isian ini wajib diisi',
            'email' => 'Kolom isian Email harus berupa alamat surel yang valid.',
        ],
        'password' => [
            'required' => 'Kolom isian Password wajib diisi',
            'minlength' => 'Kolom isian Password harus minimal 6.',
            'maxlength' => 'Kolom isian Password seharusnya tidak lebih dari 100',
        ],
    ],


];
