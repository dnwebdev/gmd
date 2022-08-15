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
                        <td style="width: 25%;"><h2 style="color:#b51fb5; float: right;">{{ $no_invoice }}</h2></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p><strong>Halo {{ $company_name }},</strong></p>
                <p style="text-align: justify">
                    Selamat, pembayaran dengan No Invoice {{ $no_invoice }} telah berhasil. 
                </p>
            </td>
        </tr>
        <br>
        <tr>
            <td colspan="3">
                <table align="center" cellpadding="5" cellspacing="0" width="680px">
                    <tbody>
                        <tr>
                            <td colspan="3">
                                <p><strong>
                                    Detail Pembayaran
                                </strong></p>
                            </td>
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
                            <td>Nama pemesan</td>
                            <td>:</td>
                            <td>{{ $name_provider }}</td>
                        </tr>
                        <tr>
                            <td>Product yang dipesan</td>
                            <td>:</td>
                            <td>Facebook Ads</td>
                        </tr>
                        <tr>
                            <td>Masa aktif iklan</td>
                            <td>:</td>
                            <td>{{ $date_active }}</td>
                        </tr>
                        <tr>
                            <td>Total harga</td>
                            <td>:</td>
                            <td>{{ number_format($total_price,0) }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <br>
        <tr>
            <td>
                <p>Selanjutnya, kami akan mengirimkan pemberitahuan jika iklan Anda sudah tayang. </p>
                <p>Terima kasih telah menggunakan mygomodo</p>
            </td>
        </tr>
    </tbody>
</table>

</body>
</html>
