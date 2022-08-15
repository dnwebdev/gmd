<?php

return [
    'title_header' => 'Majukan bisnis Anda dan tingkatkan omset pemasukan sekarang!',
    'content_header' => 'Setiap usaha atau bisnis memiliki kesempatan untuk berkembang lebih sukses. Di Gomodo, kami memiliki fitur pinjaman yang dapat dimanfaatkan mengembangkan bisnis Anda. Ajukan pinjaman sekarang dan Anda akan dapat langsung menggunakannya setelah pinjaman dikonfirmasi.',
    'previous' => 'Kembali',
    'next' => 'Selanjutnya',
    'submit' => 'Ajukan',
    'finish' => 'Selesai',
    'validation' => [
        'not_upload_yet' => 'Belum Ada File',
        'ktp' => 'Kolom isian ktp wajib diisi',
        'npwp' => 'Kolom isian npwp wajib diisi',
        'siup' => 'Kolom isian siup wajib diisi',
        'min_amount' => 'Jumlah pinjaman yang dapat diajukan min. Rp.',
        'max_amount' => 'Maks pinjaman yang dapat diajukan Rp.',
    ],
    'not_kyc' => [
        'title' => 'Verifikasi usahamu terlebih dahulu sebelum menggunakan fitur ini!',
        'description' => 'Fitur Pembiayaan dapat diakses sepenuhnya setelah usaha Anda berhasil diverifikasi. Verifikasi usaha Anda sekarang untuk dapat mengajukan pembiayaan usaha ke mitra resmi kami.',
        'button' => 'Verifikasi Usaha Saya'
    ],
    'step-1' => [
        'description' => 'Lengkapi informasi berikut untuk mengajukan pinjaman!',
        'request_amount' => 'Nominal pinjaman (min. Rp.10.000.000)',
        'loan_term' => 'Jangka waktu pinjaman'
    ],
    'step-2' => [
        'title' => 'Pinjaman Dana',
        'description' => 'Unggah dokumen persyaratan pinjaman berikut ini:',
        'documents' => [
            'corporate_check' => 'Gunakan file KTP, NPWP dan SIUP yang sudah ada',
            'personal_check' => 'Gunakan file KTP dan NPWP yang sudah ada',
            'corporate' => [
                [
                    'title' => 'KTP Calon Peminjam',
                    'name' => 'ktp'
                ],
                [
                    'title' => 'NPWP',
                    'name' => 'npwp'
                ],
                [
                    'title' => 'SIUP',
                    'name' => 'siup'
                ],
                [
                    'title' => 'Akta Pendirian',
                    'name' => 'founding_deed'
                ],
                [
                    'title' => 'Akta Perubahan (jika ada)',
                    'name' => 'change_certificate'
                ],
                [
                    'title' => 'SK Menteri',
                    'name' => 'sk_menteri'
                ],
                [
                    'title' => 'Tanda Daftar Perusahaan',
                    'name' => 'company_signatures'
                ],
                [
                    'title' => 'Laporan Keuangan (2 tahun)',
                    'name' => 'report_statement'
                ],
                [
                    'title' => 'Buku Rekening (3 bulan terakhir)',
                    'name' => 'document_bank'
                ],
            ],
            'personal' => [
                [
                    'title' => 'KTP Calon Peminjam',
                    'name' => 'ktp'
                ],
                [
                    'title' => 'NPWP',
                    'name' => 'npwp'
                ],
                [
                    'title' => 'KTP Pasangan (jika menikah)',
                    'name' => 'ktp_couples'
                ],
                [
                    'title' => 'Kartu Keluarga',
                    'name' => 'family_card'
                ],
                [
                    'title' => 'Buku Rekening (mutasi 3 bulan terakhir)',
                    'name' => 'document_bank'
                ],
            ],
        ]
    ],
    'step-3' => [
        'tnc_table' => [
            [
                'text' => 'Nominal pinjaman',
                'id' => 'request_amount'
            ],
            [
                'text' => 'Jangka Waktu Pinjaman',
                'id' => 'loan_term'
            ],
            [
                'text' => 'Suku Bunga',
                'id' => 'interest_rate',
                'value' => '1,5% - 2% per bulan'
            ],
            [
                'text' => 'Biaya Provisi',
                'id' => 'provision_fee'
            ],
            [
                'text' => 'Biaya Denda Keterlambatan',
                'id' => 'late_fee'
            ],
            [
                'text' => 'Biaya Pelunasan Tercepat',
                'id' => 'fastest_settlement_fee'
            ],
            [
                'text' => 'Biaya Administrasi',
                'id' => 'administration_fee'
            ],
            [
                'text' => 'Asuransi',
                'id' => 'insurance'
            ],
        ],
        'tnc_text' => 'Saya telah membaca dan menyetujui <a href="" class="text-primary-300" style="text-decoration: none" data-toggle="modal" data-target="#modal_default">Term and Condition</a> untuk proses peminjaman dana'
    ],
    'success' => [
        'title' => 'Anda telah berhasil melakukan pengajuan',
        'description' => 'Proses pengajuan pinjaman dana anda telah selesai dan sekarang tim kami akan segera memproses pengajuan anda agar segera terkonfirmasi. Apabila disetujui maka dana akan secara langsung masuk ke saldo anda.'
    ],
];