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
.link{
    text-decoration: none;
    color: rgba(0, 0, 0, 0.54);
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
            </td>
        </tr>
        <br>
        <tr>
            <td colspan="3">
                <br>
                <p>Kami ingin memberitahukan bahwa masa aktif iklan Anda akan berakhir pada {{ $end_date }}. Report hasil iklan Anda akan kami kirimkan maksimal H+3 setelah iklan anda berakhir. Klik <a class="link" href="{{ baseUrl('company/premium') }}" target="_blank">disini</a> untuk memperpanjang masa aktif iklan. Jika terdapat pertanyaan dapat menghubungi kami pada nomor <a class="link" href="https://api.whatsapp.com/send?phone=6281211119655&text=&source=&data=" target="_blank" style="font-weight: bold">+62 812-1111-9655</a></p>

                <p>Terimakasih atas kepercayaan Anda menggunakan Mygomodo. Semoga informasi ini bermanfaat bagi Anda.</p>
            </td>
        </tr>
    </tbody>
</table>

</body>
</html>
