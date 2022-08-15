<!DOCTYPE html>
<html>
<head>
<style type="text/css">
body{
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 14px;
}
table {
    border-collapse:collapse;
    text-align: left;
}
</style>
</head>
<body>

<table  align="center" width="680px" cellpadding="5" cellspacing="0">
    <tbody>
        <tr>
            <td colspan="3">
                <p><strong>Halo Admin Gomodo,</strong></p>
                <p style="text-align: justify">
                    Dibawah ini merupakan data orderan provider {{ $company_name }} silahkan untuk di proses
                </p>
            </td>
        </tr>
        <br>
        <tr>
            <td colspan="3">
                <tr>
                    <td colspan="3">
                        <p><strong>
                            Detail Pemesanan
                        </strong></p>
                    </td>
                </tr>
                <tr>
                    <td>Nama Pemesan</td>
                    <td colspan="2">: {{ $first_name }}
                    @if ($last_name)
                     {{ $last_name }}</td>
                    @endif
                </tr>
                <tr>
                    <td>Fitur yang dibeli</td>
                    <td colspan="2">: {{ $category_ads }}</td>
                </tr>
                <tr>
                    <td>Masa aktif iklan</td>
                    <td colspan="2">: {{ $start_date }} sampai {{ $end_date }}</td>
                </tr>
                <tr>
                    <td>Anggaran Harian</td>
                    <td colspan="2">: {{ number_format($min_budget,0) }}</td>
                </tr>
                <tr>
                    <td>Sub Total</td>
                    <td colspan="2">: {{ number_format($sub_total,0) }}</td>
                </tr>
                <tr>
                    <td>Service Fee</td>
                    <td colspan="2">: {{ number_format($service_fee,0) }}</td>
                </tr>
                @if($gxp_amount)
                <tr>
                    <td>Gxp Amount</td>
                    <td colspan="2">: - {{ number_format($gxp_amount,0) }}</td>
                </tr>
                @endif
                @if($voucher_amount)
                <tr>
                    <td>Promo Code</td>
                    <td colspan="2">: - {{ number_format($voucher_amount,0) }}</td>
                </tr>
                @endif
                @if($cashback_amount)
                <tr>
                    <td>Voucher Cashback</td>
                    <td colspan="2">: - {{ number_format($cashback_amount,0) }}</td>
                </tr>
                @endif
                <tr>
                    <td>Total tagihan</td>
                    <td colspan="2">: {{ number_format($total_price,0) }}</td>
                </tr>
                <tr>
                    <td colspan="3">
                        <p><strong>Format Iklan</strong></p>
                    </td>
                </tr>
                <tr>
                    <td>Tujuan beriklan</td>
                    <td colspan="2">: {{ $purpose }}</td>
                </tr>
                <tr>
                    <td>{{ $category_ads == 'Google Ads' ? 'Nama Usaha' : 'Judul Iklan' }}</td>
                    <td colspan="2">: {{ $title }}</td>
                </tr>
                @if (isset($title2))
                <tr>
                    <td>Nama Produk</td>
                    <td colspan="2">: {{ $title2 }}</td>
                </tr>
                @endif
                @if (isset($business_category))
                <tr>
                    <td>Jenis Bisnis Kategori</td>
                    <td colspan="2">: {{ $business_category }}</td>
                </tr>
                @endif
                <tr>
                    <td>Deskripsi iklan</td>
                    <td colspan="2">: {{ $description }}</td>
                </tr>
                <tr>
                    <td>Nama Domain</td>
                    <td colspan="2">: {{ $url }}</td>
                </tr>
                @if ($category_ads != 'Google Ads')
                <tr>
                    <td>Teks tombol ajakan</td>
                    <td colspan="2">: {{ $call_button }}</td>
                </tr>
                <tr>
                    <td>Gambar Iklan</td>
                    <td colspan="2">: Gambar terdapat di lampiran selanjutnya</td>
                </tr>
                @endif
                <tr>
                    <td colspan="3">
                        <p><strong>Target Iklan</strong></p>
                    </td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td colspan="2">: {{ $gender }}</td>
                </tr>
                @if (isset($language))
                <tr>
                    <td>Bahasa</td>
                    <td colspan="2">: {{ $language }}</td>
                </tr>
                @endif
                <tr>
                    <td>Usia</td>
                    <td colspan="2">: {{ $age }}</td>
                </tr>
                <tr>
                    <td>Lokasi</td>
                <td colspan="2">: {{ $city }}</td>
                </tr>
                <tr>
                    <td colspan="3">
                        Segera proses pesanan iklan anda
                    </td>
                </tr>
            </td>
        </tr>
    </tbody>
</table>

</body>
</html>
