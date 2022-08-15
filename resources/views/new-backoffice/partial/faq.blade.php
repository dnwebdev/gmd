@extends('new-backoffice.header')
@section('content')
  <h3 class="mt-3 mb-3">FAQ</h3>
{{--  <div class="card" id="faq-section">--}}
{{--    <div class="card-body">--}}
{{--      <ol class="fs-1rem">--}}
{{--        <li class="font-weight-bold">DAFTAR--}}
{{--          <ul>--}}
{{--            <li class="font-weight-bold">--}}
{{--              Bagaimana Saya daftar di website Bupsha?--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Daftar di website Bupsha sangat mudah, tinggal buka website Bupsha.gomodo.id kemudian Masuk (login). Selanjutnya, isi formulir yang disediakan, dan Anda sudah masuk ke website Bupsha.--}}
{{--            </li>--}}
{{--          </ul>--}}
{{--        </li>--}}
{{--        <li class="font-weight-bold">--}}
{{--          PENGGUNA--}}
{{--          <ul>--}}
{{--            <li class="font-weight-bold">--}}
{{--              Siapa saja yang bisa menjadi pengguna website?--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Pengguna website adalah seseorang yang menjadi admin website--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Siapa saja yang bisa mengisi  Profil website?--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Profil website dapat diisi untuk seseorang yang berfungsi sebagai super admin seperti IT, Akunting, dan Superadmin dari website.--}}
{{--            </li>--}}
{{--          </ul>--}}
{{--        </li>--}}
{{--        <li class="font-weight-bold">--}}
{{--          PROFIL--}}
{{--          <ul>--}}
{{--            <li class="font-weight-bold">--}}
{{--              Menu Profil digunakan untuk apa?--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Menu profil digunakan untuk menjelaskan profil detail Admin yang diberi hak untuk akses dan mengelola website.--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Bagaimana Saya dapat menjadi Admin?--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Untuk menjadi admin website, Anda dapat membuka halaman Beranda dan memilih Menu Admin. Selanjutnya, Anda dapat mengisi formulir yang disediakan.--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Data apa saja yang harus Saya isikan di menu  Admin?--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Anda bisa melengkapi isian Nama, Email, Kata Sandi, dan Foto Profil--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Apakah saya harus mengisi Kata Sandi Baru?--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Anda dapat mengisi Kata Sandi Baru jika Anda ingin mengganti sandi untuk website Anda--}}
{{--            </li>--}}
{{--          </ul>--}}
{{--        </li>--}}
{{--        <li class="font-weight-bold">--}}
{{--          PRODUK--}}
{{--          <ul>--}}
{{--            <li class="font-weight-bold">--}}
{{--              Bagaimana cara membuat produk di website Bupsha?--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Anda dapat memilih bagian Produk di halaman Beranda kemudian memilih tombol Buat Produk. Di bagian tersebut, Anda dapat mengisikan Detail Produk, Ketersediaan Waktu Produk, Harga Produk, Informasi Pelanggan, Tambahan dan Unggah gambar.--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Apakah Saya harus mengisi seluruh formulir isian di halaman ini?--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Anda tidak boleh melewatkan formulir isian yang ada tanda bintang-nya--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Apa perbedaan tipe harga produk Harga Tetap  dan Harga Grup/Kelompok ?--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Tipe harga produk  Harga Tetap digunakan untuk harga satuan untuk setiap pemesanan dan Harga Grup/Rombongan adalah harga yang digunakan dalam jumlah pemesanan yang besar.--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Apa fungsi Widget di halaman produk?--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Widget adalah elemen yang memiliki fungsi untuk memasukkan konten dan struktur tambahan pada sidebar atau footer. Widget dapat memudahkan Anda jika ingin menambahkan beberapa fitur canggih di halaman situs.--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Bagaimana jika Saya ingin mengaktifkan widget?--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Untuk mengaktifkan widget, Anda dapat menghubungi IT support Anda atau tim Bupsha.--}}
{{--            </li>--}}
{{--          </ul>--}}
{{--        </li>--}}
{{--        <li class="font-weight-bold">--}}
{{--          PEMESANAN--}}
{{--          <ul>--}}
{{--            <li class="font-weight-bold">--}}
{{--              Bagaimana Saya mengetahui produk yang dipesan pelanggan?--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Anda dapat membuka halaman Beranda dan memilih bagian Pemesanan untuk melihat produk yang dipesan oleh pelanggan Anda. Anda dapat memperoleh Daftar Pemesanan dengan rincian Tanggal Pemesanan, Nomor Invoice, Domain, Total Harga, Status, dan Aksi.--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Bagaimana metode pemesanan  produk yang disediakan?--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Untuk pemesanan, kami menerima dua metode pemesanan, yaitu melalui pemesanan online dan transaksi di lokasi.--}}
{{--            </li>--}}
{{--          </ul>--}}
{{--        </li>--}}
{{--        <li class="font-weight-bold">--}}
{{--          ANGGOTA--}}
{{--          <ul>--}}
{{--            <li class="font-weight-bold">--}}
{{--              Siapakah yang bisa menjadi anggota website Bupsha?--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Anggota website Bupsha adalah penyedia ekowisata di seluruh Indonesia--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Bagaimana Saya bisa melihat status penyedia ekowisata yang masuk anggota?--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Anda dapat melihat status penyedia ekowisata; aktif atau tidak aktif--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Apakah saya bisa menyunting atau menghapus anggota?--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Ya, Anda dapat menyunting atau menghapus informasi anggota melalui ikon yang disediakan--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Apakah Saya bisa memeriksa jumlah transaksi yang dilakukan oleh anggota?--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Ya, Anda dapat memperoleh data transaksi masing-masing pengguna. Anda juga dapat mengukur rata-rata pendapatan anggota yang terdaftar di website Bupsha.--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Apakah Saya dapat mendapatkan daftar penyedia ekowisata dengan jumlah pendapatan yang paling banyak?--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Ya. Anda dapat memperoleh Daftar pendapatan penyedia jasa yang terdaftar. Kami mengklasifikasikan ke dalam Pendapatan Gold, Platinum, Silver, dan Blue.--}}
{{--            </li>--}}
{{--          </ul>--}}
{{--        </li>--}}
{{--        <li class="font-weight-bold">--}}
{{--          NOTIFIKASI (<i>Coming Soon</i>)--}}
{{--          <ul>--}}
{{--            <li class="font-weight-bold">--}}
{{--              Bagaimana jika Saya tidak menerima pemberitahuan atas pembayaran yang sudah dilakukan oleh pelanggan Saya?--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Pertama, silakan cek kotak spam dan promo Anda, jika Anda tidak menemukan silakan hubungi Customer Success kami di  (nomor kontak)--}}
{{--            </li>--}}
{{--          </ul>--}}
{{--        </li>--}}
{{--        <li class="font-weight-bold">--}}
{{--          PESAN (<i>Coming Soon</i>)--}}
{{--          <ul>--}}
{{--            <li class="font-weight-bold">--}}
{{--              Apakah yang terdapat di Menu Pesan?--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Setiap pesan yang masuk dan keluar dari pelanggan dapat dimonitor oleh penyedia jasa melalui menu pesan.--}}
{{--            </li>--}}
{{--          </ul>--}}
{{--        </li>--}}
{{--        <li class="font-weight-bold">--}}
{{--          SEBARKAN (<i>Coming Soon</i>)--}}
{{--          <ul>--}}
{{--            <li>--}}
{{--              Menu Sebarkan digunakan untuk apa?--}}
{{--            </li>--}}
{{--            <li>--}}
{{--              Menu tersebut digunakan untuk menyebarkan informasi yang ingin disebarluaskan kepada penyedia ekowisata berkaitan dengan fitur baru, fitur yang akan datang, dan sebagainya.--}}
{{--            </li>--}}
{{--          </ul>--}}
{{--        </li>--}}
{{--      </ol>--}}
{{--    </div>--}}
{{--  </div>--}}
  @include('new-backoffice.partial.coming_soon')

@endsection