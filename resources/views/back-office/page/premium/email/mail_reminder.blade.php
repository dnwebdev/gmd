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
                <p><strong>Halo {{ $company_name }},</strong></p>
            </td>
        </tr>
        <br>
        <tr>
            <td colspan="3">
                <p>Kami ingin memberitahukan bahwa Anda belum melakukan pembayaran untuk pesanan Facebook Ads dengan nomor invoice {{ $no_invoice }}. Total tagihan Anda sebesar Rp. {{ $total_price }}</p> <br>
                <p>
                    Silahkan melakukan pembayaran melalui rekening <strong>Bank BCA 4503470147 atas nama PT Kadal Nusantara Perkasa</strong> sebelum tanggal {{ $start_date }} atau pemesanan akan secara otomatis dibatalkan.
                </p>
            </td>
        </tr>
    </tbody>
</table>

</body>
</html>
