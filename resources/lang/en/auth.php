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

    'failed' => 'These credentials do not match our records.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'not_active'=>'Account not Activated, Please Activate Your Account',
    'already_active'=>'Your account already activated',
    'suspend'=>'Your Account Has Been Suspended',
    'incorrect'=>'Email or Password Incorrect',
    'email' => 'Email Incorrect',
    'telp' => 'No Handphone Incorrect',

    'validation' => [
        'telp' => [
            'required' => 'The phone number field is required',
            'number' => 'Kolom isian Nomor Telepon harus berupa angka.',
//            'minlength' => 'Kolom isian Nomor Telepon harus minimal 9.',
            'forgot_password' => 'Seluler phone number not registered'
        ],
        'email' => [
            'required' => 'The Email field is required',
            'email' => 'The Email field must be a valid email address.',
        ],
        'password' => [
            'required' => 'Password Field Required.',
            'maxlength' => 'The Password field should be no more than 100',
        ],
    ],
];
