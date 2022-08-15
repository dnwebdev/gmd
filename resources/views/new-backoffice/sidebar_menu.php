<?php

return [
    [
        'name'  => 'Dasbor',
        'url'   => route('admin:dashboard'),
        'icon'  => 'icon-home4'
    ],
    [
        'name'  => 'Pemesanan',
        'icon'  => 'icon-cart',
        'child' => [
            [
                'name'  => 'Pemesanan Online',
                'url'   => route('admin:master.transaction.index', ['type' => 'online']),
                'icon'  => ''
            ],
            [
                'name'  => 'Transaksi di Lokasi',
                'url'   => route('admin:master.transaction.index', ['type' => 'offline']),
                'icon'  => ''
            ]
        ]
    ],
    [
        'name'  => 'Produk',
        'icon'  => 'icon-list2',
        'child' => [
            [
                'name'  => 'Rincian',
                'url'   => route('admin:product.index'),
                'icon'  => ''
            ],
            /*[
                'name'  => 'Kategori Produk',
                'url'   => url('//list-category-product'),
                'icon'  => ''
            ],*/
            [
                'name'  => 'Tag',
                'url'   => route('admin:master.product-tag.index'),
                'icon'  => ''
            ]
        ]
    ],
    [
        'name'  => 'Anggota',
        'icon'  => 'icon-users',
        'child' => [
            [
                'name'  => 'KUPS',
                'url'   => route('admin:providers.index'),
                'icon'  => ''
            ],
            [
                'name'  => 'Kategori KUPS',
                'url'   => route('admin:master.business-category.index'),
                'icon'  => ''
            ],
            [
                'name'  => 'Pendapatan',
                'url'   => url('/earning'),
                'icon'  => ''
            ]
        ]
    ],
    [
        'name'  => 'Notifikasi',
        'url'   => url('/notification'),
        'icon'  => 'icon-bell2'
    ],
    [
        'name'  => 'Pesan',
        'url'   => url('/messages'),
        'icon'  => 'icon-mail5'
    ],
    [
        'name'  => 'Sebarkan',
        'url'   => url('/broadcast'),
        'icon'  => 'icon-megaphone'
    ],
    [
        'name'  => 'Profil',
        'url'   => route('admin:profile'),
        'icon'  => 'icon-user'
    ],
    [
        'name'  => 'Pengguna',
        'url'   => route('admin:setting.admin.index'),
        'icon'  => 'icon-user-plus'
    ],
    [
        'name'  => 'Keluar',
        'url'   => route('admin:logout'),
        'icon'  => 'icon-exit'
    ],
];
