@extends('customer.master.index')
@section('content')
    <div class="bg-light-blue block-height">
        <div class="container pt-5">
            <ul class="breadcrumb">
                <li><a href="{{route('company.dashboard')}}">Home</a></li>
                <li><a href="{{route('company.premium.index','tab=my-premium')}}">{{ $orderAds->category_ads }}</a>
                </li>
                <li><a>Invoice</a></li>
            </ul>
        </div>
        <div id="payment-virtual-account" class="container pb-5">
            <div class="row">
                <div class="col-12 py-3 text-center">
                    <p class="fs-14">{!! trans('customer.invoice.payment_expired_in') !!} :</p>
                </div>
                <div class="col-12 text-center">
                    <div class="count-down">
                        <div class="number days">
                            00
                        </div>
                        <div class="desc">
                            {!! trans('customer.invoice.day') !!}
                        </div>
                    </div>
                    <div class="count-down">
                        <div class="number hours">
                            00
                        </div>
                        <div class="desc">
                            {!! trans('customer.invoice.hour') !!}
                        </div>
                    </div>
                    <div class="count-down">
                        <div class="number minutes">
                            00
                        </div>
                        <div class="desc">
                            {!! trans('customer.invoice.minute') !!}
                        </div>
                    </div>
                    <div class="count-down">
                        <div class="number seconds">
                            00
                        </div>
                        <div class="desc">
                            {!! trans('customer.invoice.second') !!}
                        </div>
                    </div>
                </div>
                {{--{{dd($res)}}--}}
                <div class="col-12 text-center py-3">
                    <h2 class="bold">
                        {{format_priceID($res['amount'])}}
                    </h2>
                    <div class="can-copy">
                        INVOICE <span class="bold data-copied">{{$res['external_id']}}</span> | <span
                                class="bold">{{$res['payer_email']}}</span>
                    </div>
                    <div class="bold">
                        {{ $orderAds->category_ads }}
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row py-3">
                        <div class="col-md-6 order-1 order-lg-0 text-center text-lg-left">
                            <span class="caption">{!! trans('customer.invoice.select_bank_transfer_method') !!}</span>
                        </div>
                        <div class="col-md-6 text-lg-right order-0 order-lg-1  mb-lg-0 mb-3 text-center">
                            <a href="{{ route('company.premium.index') }}" class="backHome">{!! trans('customer.invoice.back_to') !!} Solusi Pemasaran</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="accordion" id="accordionExample">
                                @foreach($res['available_banks'] as $available_bank)
                                    <div class="card mt-1 mb-1">
                                        <div class="card-header" id="headingOne{{$available_bank['bank_code']}}">
                                            <h5 class="btn btn-link" type="button" data-toggle="collapse"
                                                data-target="#collapseOne{{$available_bank['bank_code']}}"
                                                aria-expanded="true"
                                                aria-controls="collapseOne{{$available_bank['bank_code']}}">
                                                {{$available_bank['bank_code']}}
                                            </h5>
                                        </div>
                                        <div id="collapseOne{{$available_bank['bank_code']}}" class="collapse"
                                             aria-labelledby="headingOne{{$available_bank['bank_code']}}"
                                             data-parent="#accordionExample">
                                            <div class="card-body bank-list">
                                                <div class="col-12 text-center virtual-account can-copy">
                                                    <h3>Virtual Account # <span
                                                                class="data-copied">{{$available_bank['bank_account_number']}}</span>
                                                    </h3>
                                                    <h3>{!! trans('customer.invoice.virtual_account_name') !!}
                                                        # {{$available_bank['account_holder_name']}}</h3>
                                                </div>
                                                @if($available_bank['bank_code'] ==='BNI')
                                                    <div class="col-12">
                                                        <div class="accordion" id="accordionMenu">
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoATM">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoATM"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoATM">
                                                                        ATM
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoATM" class="collapse"
                                                                     aria-labelledby="headingTwoATM"
                                                                     data-parent="#accordionMenu">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>Masukkan kartu Anda</li>
                                                                                <li>Pilih Bahasa</li>
                                                                                <li>Masukkan PIN ATM Anda</li>
                                                                                <li>Pilih "Menu Lainnya"</li>
                                                                                <li>Pilih Transfer</li>
                                                                                <li>Pilih jenis rekening yang akan Anda
                                                                                    gunakan (Contoh: "Dari Rekening
                                                                                    Tabungan")
                                                                                </li>
                                                                                <li>Pilih "Virtual Account Billing"</li>
                                                                                <li>Masukkan nomor Virtual Account yang
                                                                                    akan dituju <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>
                                                                                </li>
                                                                                <li>Tagihan yang harus dibayarkan akan
                                                                                    muncul pada layar konfirmasi
                                                                                </li>
                                                                                <li>Konfirmasi, apabila telah sesuai,
                                                                                    lanjutkan transaksi
                                                                                </li>
                                                                                <li>Transaksi Anda telah selesai</li>
                                                                                <li>Setelah transaksi pembayaran Anda
                                                                                    selesai, invoice ini akan diperbarui
                                                                                    secara otomatis. Ini bisa memakan
                                                                                    waktu hingga 5 menit.
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoMobilebanking">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoMobileBanking"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoMobileBanking">
                                                                        Mobile Banking BNI
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoMobileBanking" class="collapse"
                                                                     aria-labelledby="headingTwoMobilebanking"
                                                                     data-parent="#accordionMenu">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>Masukkan User ID dan Password</li>
                                                                                <li>Pilih menu "Transfer"</li>
                                                                                <li>Pilih menu "Virtual Account Billing"
                                                                                    kemudian pilih rekening debet
                                                                                </li>
                                                                                <li>Masukkan nomor Virtual Account yang
                                                                                    akan dituju <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>
                                                                                    pada menu "Input Baru"
                                                                                </li>
                                                                                <li>Tagihan yang harus dibayarkan akan
                                                                                    muncul pada layar konfirmasi
                                                                                </li>
                                                                                <li>Konfirmasi transaksi dan masukkan
                                                                                    Password Transaksi
                                                                                </li>
                                                                                <li>Pembayaran Anda telah berhasil</li>
                                                                                <li>Setelah transaksi pembayaran Anda
                                                                                    selesai, invoice ini akan diperbarui
                                                                                    secara otomatis. Ini bisa memakan
                                                                                    waktu hingga 5 menit.
                                                                                </li>

                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoIbankPersonal">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoIbankPersonal"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoIbankPersonal">
                                                                        IBank Personal BNI
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoIbankPersonal" class="collapse"
                                                                     aria-labelledby="headingTwoIbankPersonal"
                                                                     data-parent="#accordionMenu">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>Ketik alamat <a
                                                                                            href="https://ibank.bni.co.id">https://ibank.bni.co.id</a>
                                                                                    kemudian klik "Enter"
                                                                                </li>
                                                                                <li>Masukkan User ID dan password</li>
                                                                                <li>Masukkan PIN ATM Anda</li>
                                                                                <li>Pilih menu "Transfer"</li>
                                                                                <li>Pilih menu "Virtual Account
                                                                                    Billing"
                                                                                </li>
                                                                                <li>Kemudian masukan nomor Virtual
                                                                                    Account <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>
                                                                                    yang hendak dibayarkan
                                                                                </li>
                                                                                <li>Pilih rekening debet yang akan
                                                                                    digunakan, kemudian tekan "Lanjut"
                                                                                </li>
                                                                                <li>Kemudian tagihan yang harus
                                                                                    dibayarkan akan muncul pada layar
                                                                                    konfirmasi
                                                                                </li>
                                                                                <li>Masukkan Kode Otentikasi Token</li>
                                                                                <li>Pembayaran Anda telah berhasil</li>
                                                                                <li>Setelah transaksi pembayaran Anda
                                                                                    selesai, invoice ini akan diperbarui
                                                                                    secara otomatis. Ini bisa memakan
                                                                                    waktu hingga 5 menit.
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoSMSbanking">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoSMSbanking"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoSMSbanking">
                                                                        SMS Banking
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoSMSbanking" class="collapse"
                                                                     aria-labelledby="headingTwoSMSbanking"
                                                                     data-parent="#accordionMenu">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>Buka aplikasi SMS Banking BNI</li>
                                                                                <li>Pilih menu "Transfer"</li>
                                                                                <li>Pilih menu "Transfer Rekening BNI"
                                                                                </li>
                                                                                <li>Masukkan nomor Virtual Account yang
                                                                                    akan dituju <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>
                                                                                </li>
                                                                                <li>Masukkan nominal transfer sesuai
                                                                                    dengan tagihan atau kewajiban Anda.
                                                                                    Nominal yang berbeda tidak dapat
                                                                                    diproses
                                                                                </li>
                                                                                <li>Pilih "Proses" kemudian "Setuju"
                                                                                </li>
                                                                                <li>Reply sms dengan ketik pin sesuai
                                                                                    perintah
                                                                                </li>
                                                                                <li>Transaksi berhasil</li>
                                                                                <li>Atau dapat juga langsung mengetik
                                                                                    sms dengan format:
                                                                                    TRF[SPASI]NomorVA[SPASI]NOMINAL dan
                                                                                    kemudian kirim ke 3346
                                                                                </li>
                                                                                <li>Contoh:
                                                                                    TRF {{$available_bank['bank_account_number']}} {{$res['amount']}}</li>
                                                                                <li>Setelah transaksi pembayaran Anda
                                                                                    selesai, invoice ini akan diperbarui
                                                                                    secara otomatis. Ini bisa memakan
                                                                                    waktu hingga 5 menit.
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoTellerBNI">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoTellerBNI"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoTellerBNI">
                                                                        Teller BNI
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoTellerBNI" class="collapse"
                                                                     aria-labelledby="headingTwoTellerBNI"
                                                                     data-parent="#accordionMenu">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>Kunjungi Kantor Cabang atau Outlet
                                                                                    BNI terdekat
                                                                                </li>
                                                                                <li>Informasikan kepada Teller, bahwa
                                                                                    ingin melakukan pembayaran "Virtual
                                                                                    Account Billing"
                                                                                </li>
                                                                                <li>Serahkan nomor Virtual Account yang
                                                                                    akan dituju <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>
                                                                                    kepada Teller
                                                                                </li>
                                                                                <li>Teller melakukan konfirmasi kepada
                                                                                    Anda
                                                                                </li>
                                                                                <li>Teller memproses transaksi</li>
                                                                                <li>Setelah transaksi pembayaran Anda
                                                                                    selesai, invoice ini akan diperbarui
                                                                                    secara otomatis. Ini bisa memakan
                                                                                    waktu hingga 5 menit.
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoAgen46">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoAgen46"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoAgen46">
                                                                        Agen 46
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoAgen46" class="collapse"
                                                                     aria-labelledby="headingTwoAgen46"
                                                                     data-parent="#accordionMenu">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>Kunjungi Agen46 terdekat (warung,
                                                                                    toko, atau kios dengan tulisan
                                                                                    Agen46)
                                                                                </li>
                                                                                <li>Informasikan kepada Agen46, bahwa
                                                                                    ingin melakukan pembayaran "Virtual
                                                                                    Account"
                                                                                </li>
                                                                                <li>Serahkan nomor Virtual Account yang
                                                                                    akan dituju <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>
                                                                                    kepada Agen46
                                                                                </li>
                                                                                <li>Agen46 melakukan konfirmasi kepada
                                                                                    Anda
                                                                                </li>
                                                                                <li>Agen46 memproses transaksi</li>
                                                                                <li>Apabila transaksi sukses anda akan
                                                                                    menerima bukti pembayaran dari
                                                                                    Agen46 tersebut
                                                                                </li>
                                                                                <li>Setelah transaksi pembayaran Anda
                                                                                    selesai, invoice ini akan diperbarui
                                                                                    secara otomatis. Ini bisa memakan
                                                                                    waktu hingga 5 menit.
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoATMBersama">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoATMBersama"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoATMBersama">
                                                                        ATM Bersama
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoATMBersama" class="collapse"
                                                                     aria-labelledby="headingTwoATMBersama"
                                                                     data-parent="#accordionMenu">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>Masukkan kartu ke mesin ATM
                                                                                    Bersama
                                                                                </li>
                                                                                <li>Pilih Transaksi Lainnya</li>
                                                                                <li>Pilih menu "Transfer"</li>
                                                                                <li>Masukkan kode Bank BNI (009) dan
                                                                                    nomor Virtual Account yang akan
                                                                                    dituju <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>
                                                                                </li>
                                                                                <li>Masukkan nominal transfer sesuai
                                                                                    tagihan atau kewajiban Anda. Nominal
                                                                                    yang berbeda tidak dapat diproses
                                                                                </li>
                                                                                <li>Konfirmasi rincian Anda akan tampil
                                                                                    di layar, cek dan tekan "Ya" untuk
                                                                                    melanjutkan
                                                                                </li>
                                                                                <li>Transaksi berhasil</li>
                                                                                <li>Setelah transaksi pembayaran Anda
                                                                                    selesai, invoice ini akan diperbarui
                                                                                    secara otomatis. Ini bisa memakan
                                                                                    waktu hingga 5 menit.
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoATMBankLain">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoATMBanklain"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoATMBanklain">
                                                                        Bank Lain
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoATMBanklain" class="collapse"
                                                                     aria-labelledby="headingTwoATMBankLain"
                                                                     data-parent="#accordionMenu">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>Pilih menu "Transfer Antar Bank"
                                                                                    atau "Transfer Online Antar Bank"
                                                                                </li>
                                                                                <li>Masukkan kode Bank BNI (009) atau
                                                                                    pilih bank yang dituju yaitu BNI
                                                                                </li>
                                                                                <li>Masukkan nomor Virtual Account yang
                                                                                    akan dituju  <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span> pada
                                                                                    kolom rekening tujuan
                                                                                </li>
                                                                                <li>Masukkan nominal transfer sesuai
                                                                                    tagihan atau kewajiban Anda. Nominal
                                                                                    yang berbeda tidak dapat diproses
                                                                                </li>
                                                                                <li>Konfirmasi rincian Anda akan tampil
                                                                                    di layar, cek dan apabila sudah
                                                                                    sesuai silahkan lanjutkan transaksi
                                                                                    sampai dengan selesai
                                                                                </li>
                                                                                <li>Transaksi berhasil</li>
                                                                                <li>Setelah transaksi pembayaran Anda
                                                                                    selesai, invoice ini akan diperbarui
                                                                                    secara otomatis. Ini bisa memakan
                                                                                    waktu hingga 5 menit.
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoOVO">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoOVO"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoOVO">
                                                                        OVO
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoOVO" class="collapse"
                                                                     aria-labelledby="headingTwoOVO"
                                                                     data-parent="#accordionMenu">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>Buka aplikasi OVO</li>
                                                                                <li>Pilih menu "Transfer"</li>
                                                                                <li>Pilih "Rekening Bank"</li>
                                                                                <li>Masukkan kode Bank BNI (009) atau
                                                                                    pilih bank yang dituju yaitu BNI
                                                                                </li>
                                                                                <li>Masukkan nomor Virtual Account yang
                                                                                    akan dituju <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>
                                                                                    pada kolom rekening tujuan
                                                                                </li>
                                                                                <li>Masukkan nominal transfer sesuai
                                                                                    tagihan atau kewajiban Anda. Nominal
                                                                                    yang berbeda tidak dapat diproses
                                                                                </li>
                                                                                <li>Pilih "Transfer"</li>
                                                                                <li>Konfirmasi rincian Anda akan tampil
                                                                                    di layar, cek dan apabila sudah
                                                                                    sesuai silahkan pilih "Konfirmasi"
                                                                                    untuk lanjutkan transaksi sampai
                                                                                    dengan selesai
                                                                                </li>
                                                                                <li>Transaksi berhasil</li>
                                                                                <li>Setelah transaksi pembayaran Anda
                                                                                    selesai, invoice ini akan diperbarui
                                                                                    secara otomatis. Ini bisa memakan
                                                                                    waktu hingga 5 menit.
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif($available_bank['bank_code'] ==='MANDIRI')
                                                    <div class="col-12">
                                                        <div class="accordion" id="accordionMenuMandiri">
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoATMMandiri">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoATMMandiri"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoATMMandiri">
                                                                        ATM
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoATMMandiri" class="collapse"
                                                                     aria-labelledby="headingTwoATMMandiri"
                                                                     data-parent="#accordionMenuMandiri">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>Masukkan kartu ATM dan pilih "Bahasa
                                                                                    Indonesia"
                                                                                </li>
                                                                                <li>Ketik nomor PIN kartu ATM</li>
                                                                                <li>Pilih menu BAYAR/BELI, kemudian
                                                                                    pilih menu MULTI PAYMENT
                                                                                </li>
                                                                                <li>Ketik kode perusahaan, yaitu "88908"
                                                                                    (88908 XENDIT), tekan "BENAR"
                                                                                </li>
                                                                                <li>Masukkan nomor Virtual Account <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>
                                                                                </li>
                                                                                <li>Isi NOMINAL, kemudian tekan
                                                                                    "BENAR"
                                                                                </li>
                                                                                <li>Muncul konfirmasi data customer.
                                                                                    Pilih Nomor 1 sesuai tagihan yang
                                                                                    akan dibayar, kemudian tekan "YA"
                                                                                </li>
                                                                                <li>Muncul konfirmasi pembayaran. Tekan
                                                                                    "YA" untuk melakukan pembayaran
                                                                                </li>
                                                                                <li>Bukti pembayaran dalam bentuk struk
                                                                                    agar disimpan sebagai bukti
                                                                                    pembayaran yang sah dari Bank
                                                                                    Mandiri
                                                                                </li>
                                                                                <li>Transaksi Anda sudah selesai</li>
                                                                                <li>Setelah transaksi pembayaran Anda
                                                                                    selesai, invoice ini akan diperbarui
                                                                                    secara otomatis. Ini bisa memakan
                                                                                    waktu hingga 5 menit.
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header"
                                                                     id="headingTwoMobilebankingMandiri">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoMobileBankingMandiri"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoMobileBankingMandiri">
                                                                        IBanking Mandiri
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoMobileBankingMandiri"
                                                                     class="collapse"
                                                                     aria-labelledby="headingTwoMobilebankingMandiri"
                                                                     data-parent="#accordionMenuMandiri">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>Kunjungi website Mandiri Internet
                                                                                    Banking dengan alamat
                                                                                    <a href="https://ib.bankmandiri.co.id">https://ib.bankmandiri.co.id</a>
                                                                                </li>
                                                                                <li>Login dengan memasukkan USER ID dan
                                                                                    PIN
                                                                                </li>
                                                                                <li>Masuk ke halaman Beranda, lalu pilih
                                                                                    "Bayar"
                                                                                </li>
                                                                                <li>Pilih "Multi Payment"</li>
                                                                                <li>Pilih "No Rekening Anda"</li>
                                                                                <li>Pilih Penyedia Jasa "88908 XENDIT"
                                                                                </li>
                                                                                <li>Masukkan nomor Virtual Account <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>
                                                                                </li>
                                                                                <li>Masuk ke halaman konfirmasi 1</li>
                                                                                <li>Apabila benar/sesuai, klik tombol
                                                                                    tagihan TOTAL, kemudian klik
                                                                                    "Lanjutkan"
                                                                                </li>
                                                                                <li>Masuk ke halaman konfirmasi 2</li>
                                                                                <li>Masuk Challenge Code yang dikirimkan
                                                                                    ke Token Internet Banking Anda,
                                                                                    kemudian klik "Kirim"
                                                                                </li>
                                                                                <li>Masuk ke halaman konfirmasi
                                                                                    pembayaran telah selesai
                                                                                </li>
                                                                                <li>Setelah transaksi pembayaran Anda
                                                                                    selesai, invoice ini akan diperbarui
                                                                                    secara otomatis. Ini bisa memakan
                                                                                    waktu hingga 5 menit.
                                                                                </li>

                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header"
                                                                     id="headingTwoIbankPersonalMandiri">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoIbankPersonalMandiri"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoIbankPersonalMandiri">
                                                                        IBank Personal BNI
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoIbankPersonalMandiri"
                                                                     class="collapse"
                                                                     aria-labelledby="headingTwoIbankPersonalMandiri"
                                                                     data-parent="#accordionMenuMandiri">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>Buka M-Banking Mandiri dan masukkan
                                                                                    PIN
                                                                                </li>
                                                                                <li>Pilih menu "Bayar"</li>
                                                                                <li>Pilih "Buat Pembayaran Baru"</li>
                                                                                <li>Pilih "Multi Payment"</li>
                                                                                <li>Pilih Penyedia Jasa "88908 XENDIT"
                                                                                </li>
                                                                                <li>Masukkan rekening tujuan <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>
                                                                                    yang hendak dibayarkan
                                                                                </li>
                                                                                <li>Masukkan Nominal transfer</li>
                                                                                <li>Beri keterangan bila perlu, kemudian
                                                                                    tekan "LANJUT"
                                                                                </li>
                                                                                <li>Masukkan MPIN Anda untuk
                                                                                    menyelesaikan transaksi
                                                                                </li>
                                                                                <li>Unduh bukti transfer sebagai bukti
                                                                                    pembayaran Anda yang sah
                                                                                </li>
                                                                                <li>Setelah transaksi pembayaran Anda
                                                                                    selesai, invoice ini akan diperbarui
                                                                                    secara otomatis. Ini bisa memakan
                                                                                    waktu hingga 5 menit.
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                @elseif($available_bank['bank_code'] ==='BRI')
                                                    <div class="col-12">
                                                        <div class="accordion" id="accordionMenuBRI">
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoATMBRI">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoATMBRI"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoATMBRI">
                                                                        ATM BRI
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoATMBRI" class="collapse"
                                                                     aria-labelledby="headingTwoATMBRI"
                                                                     data-parent="#accordionMenuBRI">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>Masukkan kartu, pilih bahasa
                                                                                    kemudian masukkan PIN Anda
                                                                                </li>
                                                                                <li>Pilih "Transaksi Lain" lalu pilih
                                                                                    "Pembayaran"
                                                                                </li>
                                                                                <li>Pilih "Lainnya" lalu pilih "Briva"
                                                                                </li>
                                                                                <li>Masukkan nomor Virtual Account <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>
                                                                                    dan nominal yang ingin Anda bayar
                                                                                </li>
                                                                                <li>Periksa kembali data transaksi
                                                                                    kemudian tekan "YA"
                                                                                </li>
                                                                                <li>Setelah transaksi pembayaran Anda
                                                                                    selesai, invoice ini akan diperbarui
                                                                                    secara otomatis. Ini bisa memakan
                                                                                    waktu hingga 5 menit.
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header"
                                                                     id="headingTwoMobilebankingBRI">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoMobileBankingBRI"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoMobileBankingBRI">
                                                                        IBanking BRI
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoMobileBankingBRI" class="collapse"
                                                                     aria-labelledby="headingTwoMobilebankingBRI"
                                                                     data-parent="#accordionMenuBRI">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>Login di
                                                                                    <a href="https://ib.bri.co.id">https://ib.bri.co.id</a>
                                                                                    masukkan USER ID dan PASSWORD
                                                                                </li>
                                                                                <li>Pilih "Pembayaran" lalu pilih
                                                                                    "Briva"
                                                                                </li>
                                                                                <li>Masukkan nomor Virtual Account Anda
                                                                                    <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>,
                                                                                    serta nominal yang akan dibayar,
                                                                                    lalu klik kirim
                                                                                </li>
                                                                                <li>Masukkan kembali PASSWORD Anda serta
                                                                                    kode otentikasi mToken internet
                                                                                    banking
                                                                                </li>
                                                                                <li>Setelah transaksi pembayaran Anda
                                                                                    selesai, invoice ini akan diperbarui
                                                                                    secara otomatis. Ini bisa memakan
                                                                                    waktu hingga 5 menit.
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header"
                                                                     id="headingTwoIbankPersonalBRI">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoIbankPersonalBRI"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoIbankPersonalBRI">
                                                                        MBanking BRI
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoIbankPersonalBRI"
                                                                     class="collapse"
                                                                     aria-labelledby="headingTwoIbankPersonalBRI"
                                                                     data-parent="#accordionMenuBRI">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>Login ke BRI Mobile Banking, masukkan USER ID dan PIN Anda
                                                                                </li>
                                                                                <li>Pilih "Pembayaran" lalu pilih "Briva"</li>
                                                                                <li>Masukkan nomor Virtual Account Anda <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>, serta nominal yang akan dibayar</li>
                                                                                <li>Masukkan nomor PIN anda dan klik "Kirim"</li>
                                                                                <li>Setelah transaksi pembayaran Anda
                                                                                    selesai, invoice ini akan diperbarui
                                                                                    secara otomatis. Ini bisa memakan
                                                                                    waktu hingga 5 menit.
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    @elseif($available_bank['bank_code'] === 'PERMATA')
                                                    <div class="col-12">
                                                        <div class="accordion" id="accordionMenuPermata">
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header" id="headingTwoATM{{ $available_bank['bank_code'] }}">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoATM{{ $available_bank['bank_code'] }}"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoATM{{ $available_bank['bank_code'] }}">
                                                                        ATM ALTO
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoATM{{ $available_bank['bank_code'] }}" class="collapse"
                                                                     aria-labelledby="headingTwoATM{{ $available_bank['bank_code'] }}"
                                                                     data-parent="#accordionMenuPermata">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                @foreach (__('customer.bank_transfer.permata.atm') as $content)
                                                                                <li>
                                                                                    @if ($loop->index === 5)
                                                                                        {!! __('customer.bank_transfer.permata.atm.5', ['va_number'=>$available_bank['bank_account_number']]) !!}
                                                                                    @else
                                                                                        {!! $content !!} 
                                                                                    @endif
                                                                                </li>
                                                                                @endforeach
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header"
                                                                     id="headingTwoMobilebanking{{ $available_bank['bank_code'] }}">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoMobileBanking{{ $available_bank['bank_code'] }}"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoMobileBanking{{ $available_bank['bank_code'] }}">
                                                                        Internet Banking
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoMobileBanking{{ $available_bank['bank_code'] }}" class="collapse"
                                                                     aria-labelledby="headingTwoMobilebanking{{ $available_bank['bank_code'] }}"
                                                                     data-parent="#accordionMenuPermata">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                @foreach (__('customer.bank_transfer.permata.ibanking') as $content)
                                                                                <li>
                                                                                    @if ($loop->index === 4)
                                                                                        {!! __('customer.bank_transfer.permata.ibanking.4', ['va_number'=>$available_bank['bank_account_number']]) !!}
                                                                                    @else
                                                                                        {!! $content !!} 
                                                                                    @endif
                                                                                </li>
                                                                                @endforeach
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header"
                                                                     id="headingTwoIbankPersonal{{ $available_bank['bank_code'] }}">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseTwoIbankPersonal{{ $available_bank['bank_code'] }}"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseTwoIbankPersonal{{ $available_bank['bank_code'] }}">
                                                                        Permata Mobile
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseTwoIbankPersonal{{ $available_bank['bank_code'] }}"
                                                                     class="collapse"
                                                                     aria-labelledby="headingTwoIbankPersonal{{ $available_bank['bank_code'] }}"
                                                                     data-parent="#accordionMenuPermata">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                @foreach (__('customer.bank_transfer.permata.mbanking') as $content)
                                                                                <li>
                                                                                    @if ($loop->index === 4)
                                                                                        {!! __('customer.bank_transfer.permata.mbanking.4', ['va_number'=>$available_bank['bank_account_number']]) !!}
                                                                                    @else
                                                                                        {!! $content !!} 
                                                                                    @endif
                                                                                </li>
                                                                                @endforeach
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header"
                                                                     id="headingThreeIbankPersonal{{ $available_bank['bank_code'] }}">
                                                                    <h5 class="btn btn-link" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseThreeIbankPersonal{{ $available_bank['bank_code'] }}"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseThreeIbankPersonal{{ $available_bank['bank_code'] }}">
                                                                        Permata Mobile X
                                                                    </h5>
                                                                </div>
                                                                <div id="collapseThreeIbankPersonal{{ $available_bank['bank_code'] }}"
                                                                     class="collapse"
                                                                     aria-labelledby="headingThreeIbankPersonal{{ $available_bank['bank_code'] }}"
                                                                     data-parent="#accordionMenuPermata">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                @foreach (__('customer.bank_transfer.permata.xbanking') as $content)
                                                                                <li>
                                                                                    @if ($loop->index === 3)
                                                                                        {!! __('customer.bank_transfer.permata.xbanking.3', ['va_number'=>$available_bank['bank_account_number']]) !!}
                                                                                    @else
                                                                                        {!! $content !!} 
                                                                                    @endif
                                                                                </li>
                                                                                @endforeach
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script>
        let expired_in = parseInt("{{strtotime($res['expiry_date'])}}");
        let skrg = parseInt("{{strtotime(date('Y-m-d H:i:s'))}}");
        let countdownTimer;
        let seconds = (expired_in - skrg);
        if (expired_in > skrg) {
            countdownTimer = setInterval('timer()', 1000);
        }else{
            checkOrder();
        }

        function pad(n) {
            return (n < 10 ? "0" + n : n);
        }

        function timer() {
            let days = Math.floor(seconds / 24 / 60 / 60);
            let hoursLeft = Math.floor((seconds) - (days * 86400));
            let hours = Math.floor(hoursLeft / 3600);
            let minutesLeft = Math.floor((hoursLeft) - (hours * 3600));
            let minutes = Math.floor(minutesLeft / 60);
            let remainingSeconds = seconds % 60;
            checkOrder();
            $('.days').html(pad(days));
            $('.hours').html(pad(hours));
            $('.minutes').html(pad(minutes));
            $('.seconds').html(pad(remainingSeconds));
            if (seconds === 0) {
                clearInterval(countdownTimer);
            } else {
                seconds--;
            }
        }

        function checkOrder() {
            let data= {
              'no_invoice':'{{$orderAds->no_invoice}}'
            };
            $.ajax({
                url:'{{route('invoice-ads.check-order')}}',
                data:data,
                success:function (data) {

                },
                error:function (e) {
                    if (e.responseJSON.result!==undefined){
                        window.location = e.responseJSON.result.redirect
                    }
                }
            })
        }
    </script>
@stop
