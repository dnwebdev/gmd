<?php

return [
    // Menu Kode Promo Page
    'menu' => [
        'voucher' => 'Kode Promo',
        'new_voucher' => 'Kode Promo Baru',
        'list_of_voucher' => 'Daftar Kode Promo',
        'voucher_code' => 'Kode Kode Promo',
        'amount' => 'Jumlah Potongan/Diskon',
        'minimum_transaction' => 'Transaksi Minimum',
        'description' => 'Deskripsi',
        'status' => 'Status',
        'no_voucher_yet' => '-- Belum ada kode promo --',
        'voucher_used' => 'Kode Promo yang digunakan',
        'create_voucher' => 'Buat Kode Promo Baru',
        'edit_voucher' => 'Edit Kode Promo'
    ],
    // Create Kode Promo
    'create' => [
        'create_new_voucher' => 'Membuat Kode Promo Baru',
        'voucher_inforation' => 'Kode Promo Baru',
        'voucher_code' => 'Kode Promo',
        'voucher_currency' => 'Mata Uang',
        'voucher_currency_empty' => 'Pilih Mata Uang',
        'voucher_ammount_type' => 'Jenis Potongan',
        'voucher_ammount_type_empty' => 'Pilih Jenis Potongan',
        'voucher_ammount' => 'Jumlah Potongan/Diskon',
        'minimum_transaction_ammount' => 'Min. Transaksi',
        'max_use' => 'Maks. Penggunaan',
        'voucher_status' => 'Status',
        'voucher_description' => 'Deskripsi',
        'voucher_validity' => 'Validitas Kode Promo',
        'valid_start_date' => 'Tanggal Mulai',
        'valid_end_date' => 'Tanggal Berakhir',
        'schedule_start_date' => 'Tanggal Mulai Jadwal',
        'schedule_end_date' => 'Tanggal Berakhir Jadwal',
        'submit' => 'Simpan',
        'active' => 'Aktif',
        'not_active' => 'Tidak Aktif',
        'fix_amount' => 'Nominal Tetap',
        'percentage' => 'Persentase',
        'min_people' => 'Min. Peserta/Unit per Pesanan',
        'max_people' => 'Maks. Peserta/Unit per Pesanan',
        'product'    => 'Promo Berlaku untuk Produk:'
    ],
    // proses submit
    'process' => [
        'store_submit' => 'Kode Promo baru berhasil dibuat',
        'update_submit' => 'Kode Promo berhasil disimpan',
        'success' => 'Sukses',
        'oops' => 'Gagal...',
    ],
    // validasi error
    'validate' => [
        'voucher_code' => 'Kode promo harus diisi',
        'voucher_code_unique' => 'Kode promo harus unik dan belum pernah digunakan.',
        'voucher_description' => 'Deskripsi harus diisi',
        'minimum_amount' => 'Jumlah minimum harus diisi (Min 0)',
        'voucher_amount' => 'Format jumlah kode promo tidak valid',
        'voucher_amount_min' => 'Jumlah kode promo harus minimal 1.',
    ],
];
