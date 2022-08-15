<?php
return [
    'success_cod' => [
        'payment_on_site' => 'Pembayaran di Tempat',
        'title' => 'Terima kasih telah melakukan pemesanan',
        'description' => 'Silahkan menyelesaikan pembayaran Anda dengan :company dengan cara pembayaran di tempat',
        'sub_description' => 'Jika Anda memiliki pertanyaan, silahkan menghubungi kami di',
        'whatsapp' => 'Kontak kami di Whatsapp',
        'email' => 'Email ke :email', 
        'back' => 'Kembali ke Beranda'
    ],
    'validation-ovo' => [
        'check_phone' => 'Silakan cek aplikasi OVO Anda',
        'api_validation_error' => 'Ada masukan yang tidak valid di salah satu kolom permintaan yang dibutuhkan.',
        'user_did_not_authorize_the_payment' => 'User tidak mengotorisasi permintaan pembayaran dalam batas waktu.',
        'user_declined_the_transaction' => 'User menolak permintaan pembayaran.',
        'phone_number_not_registered' => 'Nomor telepon pengguna yang mencoba membayar tidak terdaftar.',
        'external_error' => 'Ada kesalahan pada sisi penyedia eWallet. Silakan hubungi dukungan kami untuk bantuan lebih lanjut.',
        'sending_transaction_error' => 'Kita tidak bisa mengirim transaksi. Silakan hubungi dukungan kami untuk bantuan lebih lanjut.',
        'ewallet_app_unreachable' => 'Penyedia eWallet/server tidak dapat mencapai aplikasi/telepon pengguna eWallet. Kasus umum adalah aplikasi eWallet dihapus.',
        'duplicate_payment' => 'Pembayaran dengan Invoice yang sama sudah dibuat sebelumnya.',
        'ewallet_type_not_supported' => 'Tipe Ewallet yang diminta belum didukung.',
        'development_mode_payment_acknowledged' => 'Payment hanya bisa digunakan dalam mode live, jika ingin testing lakukan sesuai prosedur',
        'request_forbidden_error' => 'Kunci API yang digunakan tidak memiliki izin yang diperlukan untuk melakukan permintaan.',
        'ovo_timeout_error' => 'Ada koneksi timeout dari OVO App ke OVO server',
        'credentials_error' => 'Merchant tidak terdaftar dalam sistem penyedia e-wallet ',
        'account_authentication_error' => 'Autentikasi pengguna telah gagal',
        'account_blocked_error' => 'Tidak dapat memproses transaksi karena akun pengguna diblokir',
        'development_mode_payment_simulation_acknowledged' => 'Pembayaran Anda telah diakui. Harap gunakan jumlah 80001 untuk mensimulasikan pembayaran yang berhasil',
        'duplicate_payment_request_error' => 'Sudah ada permintaan pembayaran'
    ],
    'validation_linkaja' => [
        'success_payment' => 'Respon untuk transaksi e-wallet yang berhasil dibayar',
        'failed_payment' => 'Ada kesalahan ketika pembayaran diproses'
    ]
];