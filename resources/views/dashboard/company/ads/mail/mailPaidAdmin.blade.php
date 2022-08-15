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
                <table width="100%">
                    <tr>
                        <td colspan="2" class="provider-logo">
                            <img src="{{ asset('img/gomodo.png') }}" height="50" alt="Logo Gomodo">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p><strong>Halo Admin,</strong></p>
                <p style="text-align: justify">
                    Berikut ini merupakan informasi transaksi yang telah dilakukan oleh customer {{ $company_name }}:
                </p>
            </td>
        </tr>

        <tr>
            <td colspan="3">
                <p><strong>Detail Pembayaran</strong></p>
                <table align="center" cellpadding="5" cellspacing="0" width="680px">
                    <tbody>
                        <tr>
                            <td>Nomor Invoice</td>
                            <td>:</td>
                            <td>{{ $no_invoice }}</td>
                        </tr>
                        <tr>
                            <td>Nama pemesan</td>
                            <td>:</td>
                            <td>{{ $first_name }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal pembayaran</td>
                            <td>:</td>
                            <td>{{ $updated_at }}</td>
                        </tr>
                        <tr>
                            <td>Waktu pembayaran</td>
                            <td>:</td>
                            <td>{{ $time_updated_at }}</td>
                        </tr>
                        <tr>
                            <td>Produk yang dipesan</td>
                            <td>:</td>
                            <td>{{ $category_ads }}</td>
                        </tr>
                        <tr>
                            <td>Masa aktif iklan</td>
                            <td>:</td>
                            <td>{{ $date_active }}</td>
                        </tr>
                        <tr>
                            <td>Sub total</td>
                            <td>:</td>
                            <td>IDR {{ number_format($orderAds->amount,0) }}</td>
                        </tr>
                        <tr>
                            <td>Service Fee</td>
                            <td>:</td>
                            @if (isset($orderAds->adsOrder))
                            <td>IDR {{ number_format($orderAds->adsOrder->service_fee,0) }}</td>
                            @endif
                        </tr>
                        @if ($orderAds->promoAds)
                        <tr>
                            <td>Voucher By Gomodo ($orderAds->promoAds->code)</td>
                            <td>:</td>
                            <td>IDR {{ number_format($orderAds->voucher,0) }}</td>
                        </tr>
                        @endif
                        @if ($orderAds->voucherAds)
                        <tr>
                            <td>Voucher Cashback</td>
                            <td>:</td>
                            <td>IDR {{ number_format($orderAds->voucherAds->nominal,0) }}</td>
                        </tr>
                        @endif
                        @if ($orderAds->gxp_amount)
                        <tr>
                            <td>Gxp Amount</td>
                            <td>:</td>
                            <td>IDR {{ number_format($orderAds->gxp_amount,0) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td>Total harga</td>
                            <td>:</td>
                            <td>IDR {{ number_format($total_price,0) }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>:</td>
                            <td>Berhasil</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <p>Powered By GOMODO</p>
            </td>
        </tr>
    </tbody>
</table>

</body>
</html>
