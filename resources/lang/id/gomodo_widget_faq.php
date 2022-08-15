<?php 

return [
    'caption' => 'Gomodo Widget',
    'content' => [
        [
            'caption' => 'Apa itu Widget?',
            'description' => 'Widget adalah sebuah aplikasi yang dapat diletakkan di dalam web dengan memasukkan beberapa kode ke dalam halaman web tersebut. Widget bisa berupa gambar, penampil multimedia konten <i>(Flash)</i>, video, tautan media sosial, tombol pemesanan, dll. Setiap situs web ada yang telah memiliki <i>widget</i> sendiri yang biasa disebut <i>widget</i> bawaan. Tetapi, ada juga <i>widget</i> yang didapatkan dari penyedia <i>widget</i> berbayar atau gratis.'
        ],
        [
            'caption' => 'Apakah Gomodo menyediakan Widget?',
            'description' => 'Ya, fitur Gomodo ini memberi kemudahan dalam meletakkan <i>widget</i> di web Anda sendiri. Widget yang disediakan Gomodo ini gratis. Widget berupa <i>booking button</i> atau tombol pemesanan.'
        ],
        [
            'caption' => 'Apa Fungsi Widget Gomodo?',
            'description' => 'Untuk memunculkan tombol pemesanan  di situs web Anda agar pelanggan lebih mudah dalam melakukan pemesanan. Pelanggan Anda tak perlu lagi pergi ke situs gomodo.id Anda. Mereka hanya perlu menekan tombol Gomodo Widget yang telah dipasang di situs web  Anda.'
        ],
        [
            'caption' => 'Bagaimana Cara mengaktifkan Widget Gomodo di situs web Anda?',
            'setting-step' => [
                [
                    'title' => '<b>Langkah pertama:</b> buka menu produk dan pilih daftar produk seperti gambar di bawah ini.',
                    'desc' => '<img class="img-fluid" src="/landing/img/widget-step/id-widget-step-1.png" alt="widget-step-1" />'
                ],
                [
                    'title' => '<b>Langkah kedua:</b> pilih simbol plus dari produk yang ingin dibuat widgetnya.',
                    'desc' => '<img class="img-fluid" src="/landing/img/widget-step/id-widget-step-2.png" alt="widget-step-2" />'
                ],
                [
                    'title' => '<b>Langkah ketiga:</b> pilih tombol “Buat Widget” seperti gambar di bawah ini.',
                    'desc' => '<img class="img-fluid" src="/landing/img/widget-step/id-widget-step-3.png" alt="widget-step-3" /> <p>sehingga kode <i>widget</i> produk akan ditampilkan seperti gambar berikut:</p> <img class="img-fluid" src="/landing/img/widget-step/id-widget-step-3a.png" alt="widget-step-3" />'
                ],
                [
                    'title' => '<b>Langkah keempat:</b> klik “salin” untuk menyalin kode yang sudah disediakan di halaman produk Gomodo yang nantinya akan ditempelkan di website Anda di luar Gomodo.',
                ],
                [
                    'title' => '<b>Langkah kelima:</b> login melalui akun website (wordpress) Anda dan arahkan <i>mouse</i> ke menu Appearance → <i>widget</i> seperti gambar di bawah ini.',
                    'desc' => '<img class="img-fluid" src="/landing/img/widget-step/id-widget-step-5a.png" alt="widget-step-5" /> <p>Di bagian widget, seret dan lepaskan custom HTML ke <i>sidebar</i> seperti gambar di bawah ini.</p> <img class="img-fluid" src="/landing/img/widget-step/id-widget-step-5b.png" alt="widget-step-5" /><img class="img-fluid" src="/landing/img/widget-step/id-widget-step-5c.png" alt="widget-step-5" /><p>Sekarang, Anda dapat membuat judul untuk <i>widget</i> Anda dan tempelkan kode sebelumnya di bagian <i>content</i> dan klik <i>done</i>.</p><img class="img-fluid" src="/landing/img/widget-step/id-widget-step-5d.png" alt="widget-step-5" /><p>Lalu, klik <i>save</i>.</p><p>Begini contoh Widget yang sudah terpasang di halaman produk Anda.</p><img class="img-fluid" src="/landing/img/widget-step/id-widget-step-5e.png" alt="widget-step-5" /><p>Jika Anda klik tombol “Book Now”, akan tampil detail pemesanan harga, total harga, tanggal keberangkatan, dan lain-lain seperti gambar berikut ini:</p><img class="img-fluid" src="/landing/img/widget-step/id-widget-step-5f.png" alt="widget-step-5" /><p>Pelanggan Anda nantinya bisa melakukan proses pemesanan dengan menekan tombol pesan ini.</p>'
                ],
            ]
        ],
        [
            'caption' => 'Bagaimana Cara Menyesuaikan Widget Gomodo Anda?',
            'description' => 'Gomodo <i>widget</i> memiliki tampilan bawaan (default). Kotak berwarna biru, tulisannya: “Book with Gomodo” dengan font berwarna putih.</p> <p>Beberapa kode yang perlu ditambahkan untuk mengubah tampilan widget:',
            'data-list' => [
                'data-background="(isi warna latar <i>widget</i> yang diinginkan, contoh: black, untuk hitam)"',
                'data-title=”(isi judul <i>widget</i> yang diinginkan, contoh: Pesan sekarang)”',
                'data-color=”(Isi warna judul yang diinginkan, contoh: green, untuk hijau)”',
                'data-align=”(left, right, center. Jika ingin meletakkan posisi widget. kiri=left, kanan=right, tengah=center. jika dibiarkan kosong, posisi <i>widget</i> akan otomatis di tengah)”'
            ],
            'code-set' => [
                [
                    'prefix' => 'Contoh lengkapnya sebagai berikut:',
                    'code' => 'data-background="green" data-title="Pesan Sekarang" data-color="white" data-align="right"'
                ],
                [
                    'prefix' => 'sehingga menjadi:',
                    'code' => '<div><a class="gomodoEmbed" data-url=http://pandatour.mygomodo.com/product/detail/SKU5257015458132139507 data-background="green" data-title="Pesan Sekarang" data-color="white" data-align="right"></a><script src="http://mygomodo.com/gomodo-widget.js"></script></div>'
                ],
            ]
        ],
    ]
];
