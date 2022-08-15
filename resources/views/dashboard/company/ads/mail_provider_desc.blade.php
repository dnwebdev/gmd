<!DOCTYPE html>
<html>
<head>
<style type="text/css">
body{
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 14px;
    color: rgba(0, 0, 0, 0.54);
}
table {
    border-collapse:collapse 
}
.details {
    height: 100px; 
    width: 100%;
}
.details th, .details td {
    background-color: rgb(239,247,254);
}
.caption {
    color: rgb(59,70,80);
    font-weight: bold;
}
.details th, .details td {
    padding: 15px;
}
.notes {
    font-size: 11px;
    line-height: 1.2rem;
}
.total {
    float: right;
    padding-right: 8%
}
.discount {
    color: green;
}
.details tr:nth-child(even) {
    background-color: rgb(239,247,254);
}
.details tr:nth-child(odd) {
    background-color: rgb(254, 254, 255);
}
.padding td{
    padding: 5px 40px 5px 0px;
}
.details .first {
    padding-left: 3%; width: 20%;
}
.details .second {
    padding-left: 3%; width: 43%
}
.details .third {
    padding-left: 8%; width: 23%;
    font-weight: bold;
}
.details .third span {
    float: right;
}

.details .fourd {
    float: right;
    padding-top: 1.2rem;
    padding-right: 1rem;
    font-weight: bold;
    width: 100%;
    height: 100%;
}

.link{
    text-decoration: none;
    color: rgba(0, 0, 0, 0.54);
}

.email{
    font-weight: bold; 
    text-decoration: underline;
    color: #b51fb5;
}

.faq{
    font-style: italic;
    text-align: center;
}

.faq-link {
    text-decoration: underline;
    color: #b51fb5;
    font-weight: bold;
}
.btn {
    padding: 10px;
    border-radius: 5px;
    color: blueviolet;
    text-decoration: none;
}

</style>
</head>
<body>

<table  align="center" width="680px" cellpadding="5" cellspacing="0">
    <tbody>
        <tr>
            <td colspan="3">
                <table width="100%">
                    <tr>
                        <td colspan="2" class="provider-logo">
                            <img src="{{asset('landing/img/Logo-Gomodo.png')}}" height="50" alt="Logo Gomodo">
                        </td>
                        <td style="width: 25%;"><h2 style="color:#b51fb5; float: right;">{{ $no_invoice }}</h2></td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr>
            <td colspan="3">
                <p class="caption">Halo {{ $company_name }},</p>
                <p style="text-align: justify">
                    Terima kasih sudah memesan fitur {{ $category_ads }} Solusi Pemasaran di Gomodo. Pemesanan Anda sudah dikonfirmasi. Mohon segera lakukan pembayaran sesuai dengan invoice yang kami lampirkan pada email ini. Pembayaran dapat dilakukan dalam waktu <strong>2x24.</strong>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table align="center" cellpadding="5" cellspacing="0" width="680px">
                    <tbody>
                        <tr>
                            <td>Nama Pemesan</td>
                            <td>{{ $first_name }}
                            @if ($last_name)
                            {{ $last_name }}
                            @endif
                            </td>
                            <td>Tanggal Pemesanan</td>
                            <td>{{ \Carbon\Carbon::now()->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td>Nama Perusahaan</td>
                            <td>{{ $company_name }}</td>
                            <td>Metode Pembayaran</td>
                            <td>{{ $payment_method_name }}</td>
                        </tr>
                        <tr>
                            <td>No. Telepon</td>
                            <td>{{ $phone_company }}</td>
                            <td>Email</td>
                            <td class="link">{{ $email_company }}</td>
                        </tr>
                        <tr>
                            <td>Promo Voucher</td>
                            <td>
                                @if ($voucher_code)
                                {{ $voucher_code }}
                                @else
                                -
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3" style=" padding-top: 5%">
                <label class="caption" for="">DETAIL PEMESANAN</label>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table class="details" align="center" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr align="left">
                            <th class="first">Produk</th>
                            <th class="second">Masa Aktif</th>
                            <th class="third">Harga</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="first">{{ $category_ads }}</td>
                            <td class="second">{{ $start_date }} - {{ $end_date }}</td>
                            <td class="third">IDR</td>
                            <td class="fourd">{{ number_format($min_budget,0) }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
           <td colspan="3">
                <table class="total padding" width="100%">
                    <tr>
                        <td style="width: 45%"></td>
                        <td>Subtotal</td>
                        <td><strong>IDR</strong></td>
                        <td class="total"><strong>{{ number_format($sub_total,0) }}</strong></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Service Fee</td>
                        <td><strong>IDR</strong></td>
                        <td class="total"><strong>{{ number_format($service_fee,0) }}</strong></td>
                    </tr>
                    @if ($fee_credit_card)
                    <tr>
                        <td></td>
                        <td>Fee Credit Card</td>
                        <td><strong>IDR</strong></td>
                        <td class="total"><strong>{{ number_format($fee_credit_card,0) }}</strong></td>
                    </tr>
                        
                    @endif
                    @if ($gxp_amount)
                    <tr>
                        <td></td>
                        <td>Gxp Credits</td>
                        <td><strong>IDR</strong></td>
                        <td class="total discount"><strong>- {{ number_format($gxp_amount,0) }}</strong></td>
                    </tr>
                    @endif
                    @if ($voucher_amount)
                    <tr>
                        <td></td>
                        <td>Voucher</td>
                        <td><strong>IDR</strong></td>
                        <td class="total discount"><strong>- {{ number_format($voucher_amount,0) }}</strong></td>
                    </tr>
                    @endif
                    @if ($cashback_amount)
                    <tr>
                        <td></td>
                        <td>My Voucher</td>
                        <td><strong>IDR</strong></td>
                        <td class="total discount"><strong>- {{ number_format($cashback_amount,0) }}</strong></td>
                    </tr>
                    @endif
                    <tr>
                        <td></td>
                        <td>Total pembayaran</td>
                        <td ><strong>IDR</strong></td>
                        <td class="total"><strong>{{ number_format($total_price,0) }}</strong></td>
                    </tr>
                </table>
           </td> 
        </tr>
        <tr>
            <td colspan="3" align="center" style="padding-top: 30px;">
                <a class="btn" href="{{ route('invoice-ads.virtual-account',['invoice' => $no_invoice]) }}" style="background-color: #b51fb5; color: #FFFFFF;">Bayar Sekarang</a>
            </td>
        </tr>
        {{-- <tr>
            <td colspan="3">
                <p>Selanjutnya silakan lakukan pembayaran sebesar <strong>IDR {{ number_format($total_price,0) }}</strong> dengan transfer ke rekening berikut ini:</p>
            </td>
        </tr> --}}
{{--        <tr>--}}
{{--            <td colspan="3" align="center" style="padding-top: 30px;">--}}
{{--                --}}{{-- <a class="btn" href="{{ route('company.premium.index','tab=my-premium') }}">Bayar Sekarang</a> --}}
{{--                <a class="btn" href="{{ route('invoice-ads.virtual-account',['invoice' => $no_invoice]) }}" style="background-color: #b51fb5; color: #FFFFFF;">Bayar Sekarang</a>--}}
{{--            </td>--}}
{{--        </tr>--}}
        {{-- <tr>
            <td colspan="3" align="center"><strong>
                <p>Bank BCA 4503470147 atas nama PT Kadal Nusantara Perkasa</p>
            </strong></td>
        </tr>
        <tr>
            <td colspan="3">
                <p>Setelah melakukan pembayaran harap mengunggah <strong>bukti transfer, no Invoice, nama pemesan/ pemilik rekening </strong> dengan cara me reply E-mail ini.</p>
            </td>
        </tr> --}}
        <tr>
            <td colspan="3">
                <p style="font-size: 12px;">
                    <strong>Disclaimer :</strong> <br>
                    <ul>
                        <li>Pembayaran dapat dilakukan dalam <strong>waktu 2-24 jam</strong>, jika Anda tidak melakukan pembayaran dalam tenggat waktu yang ditentukan, pesanan iklan akan dibatalkan secara otomatis.</li>
                        <li>Pembuatan iklan akan diproses setelah pembayaran dilakukan.</li>
                        <li>Pemesanan yang dilakukan pada hari Sabtu, Minggu, ataupun hari libur akan diproses pada hari kerja berikutnya.</li>
                        <li>Konten dan materi iklan tidak dapat diubah setelah pembayaran dilakukan.</li>
                        <li>Jika ada pertanyaan lebih lanjut mengenai invoice ini silakan menghubungi  team Customer Success kami di 
                            <a class="link" href="https://api.whatsapp.com/send?phone=6281211119655&text=&source=&data=" target="_blank" style="font-weight: bold">+62 812-1111-9655</a> 
                            atau <span class="email">info@gomodo.tech</span>
                        </li>
                    </ul>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p class="faq">
                    Terima kasih sudah melakukan pemesanan Solusi Pemasaran di mygomodo.com. Klik di <a href="{{ baseUrl('company/premium') }}" target="_blank" class="faq-link">sini</a> untuk memesan iklan berikutnya atau kunjungi laman <a href="https://medium.com/gomodo-user-guides-english/gomodo-boost10x-capai-10x-transaksi-dapatkan-voucher-premium-store-senilai-total-rp-300-ribu-d79eb8463d9d" target="_blank" class="faq-link">FAQ</a>.
                </p>
            </td>
        </tr>
    </tbody>
</table>

</body>
</html>
