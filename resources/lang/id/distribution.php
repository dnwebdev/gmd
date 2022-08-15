<?php

return [
    'intro' => [
        'title' => 'Sistem Distribusi di Gomodo',
        'description' => 'Sistem Distribusi di Gomodo merupakan salah satu aspek pemasaran, di mana Gomodo akan membantu provider untuk mendistribusikan produk yang provider miliki ke kanal-kanal (channels) penjualan yang sudah bekerja sama dengan Gomodo baik itu Global Distribution System (GDS), Online Travel Agent (OTA) maupun Marketplace. Sehingga dengan hanya menggunakan sistem distribusi Gomodo, produk yang provider miliki bisa disalurkan ke berbagai saluran pemasaran lainnya.',
        'button_text' => 'Pelajari Selengkapnya'
    ],
    'form' => [
        'title' => 'Ajukan Permintaan Distribusi',
        'subtitle'=> 'Provider',
        'input' => [
            [
                'label' => 'Nama Anda atau Nama Perusahaan',
                'type' => 'text',
                'name' => 'name',
                'additional_class' => ''
            ],
            [
                'label' => 'Email Anda',
                'type' => 'text',
                'name' => 'email',
                'additional_class' => ''
            ],
            [
                'label' => 'Nomor Telepon',
                'type' => 'text',
                'name' => 'phone',
                'additional_class' => 'number'
            ],
            [
                'label' => 'Pesan Anda',
                'type' => 'textarea',
                'name' => 'message',
                'additional_class' => ''
            ]
        ],
        'submit' => 'Kirim Permintaan',
        'contact_us' => 'Info lebih lanjut hubungi kami di: ',
        'swall' => [
            'success' => 'Terima kasih, permintaan distribusi Anda berhasil dikirim. Tim kami akan segera menghubungi Anda untuk langkah selanjutnya.',
            'ok' => 'Mengerti'
        ],
        'already_exists'=>'Anda telah mengirimkan permintaan distribusi. Mohon menunggu tim kami akan segera menghubungi Anda',
    ]
];