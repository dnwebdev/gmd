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
.details th {
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
    color: rgb(4,155,248)
}
/*.details tr:nth-child(even) {*/
/*    background-color: rgb(239,247,254);*/
/*}*/
.details tr:nth-child(odd) {
    background-color: rgb(254, 254, 255);
}
.padding td{
    padding: 5px 0;
}
.details .first {
    padding-left: 3%; width: 20%;
}
.details .second {
    padding-left: 3%; width: 25%
}
.details .third {
    padding-left: 3%; width: 15%
}
.details .fourth {
    padding-left: 8%; width: 30%
}
.details .fourth span {
    float: right;
}
/* .provider-logo {
    background-color: rgba(0, 0, 0, 0.021);
    color: rgb(104, 104, 104);
    text-align: center;
    padding: 0;
    width: 25%;
    font-weight: bold;
    font-size: 1.2rem;
} */
.button {
    padding: 10px 100px;
    background-color: rgb(4,155,248);
    border-radius: 5px;
    color: white;
    font-size: 15px;
    position: relative;
    left: 50%;
    transform: translate(-50%,0);
    margin-top: 60px;
    text-decoration: none;
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
</style>
</head>
<body>

{{--Language English--}}
<table  align="center" width="680px" cellpadding="5" cellspacing="0">
    <tbody>
        <tr>
            <td colspan="3">
                <table width="100%">
                    <tr>
                        <td colspan="2" class="provider-logo">
                            <img src="{{asset('landing/img/Logo-Gomodo.png')}}" height="50" alt="Logo"/>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p class="caption">Hello {{$company_name}},</p>
                <p>
                    We receive withdrawal request here are the details :
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table align="center" cellpadding="5" cellspacing="0" width="680px">
                    <tbody>
                        <tr>
                            <td>Amount</td>
                            <td>IDR {{$amount}}</td>
                            <td>Code Bank</td>
                            <td>{{$bank_code}}</td>
                        </tr>
                        <tr>
                            <td>Account Name</td>
                            <td>{{$account_holder_name}}</td>
                            <td>Account Number</td>
                            <td>{{$account_number}}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p>If you did not make this withdrawal request, please change your password immediately and contact customer service at <a class="link" href="https://api.whatsapp.com/send?phone=6281211119655&text=&source=&data=" target="_blank" style="font-weight: bold">+62 812-1111-9655</a>
                            or <span class="email">info@gomodo.tech</span>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p>
                    Thank you for using mygomodo.com
                    <br>Powered by Gomodo
                </p>
            </td>
        </tr>
    </tbody>
</table>
<hr>
{{--Language Indonesia--}}
<table  align="center" width="680px" cellpadding="5" cellspacing="0">
    <tbody>
        <tr>
            <td colspan="3">
                <p class="caption">Helo {{$company_name}},</p>
                <p>
                    Kami menerima permintaan penarikan berikut rinciannya :
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table align="center" cellpadding="5" cellspacing="0" width="680px">
                    <tbody>
                        <tr>
                            <td>Jumlah</td>
                            <td>IDR {{$amount}}</td>
                            <td>Kode Bank</td>
                            <td>{{$bank_code}}</td>
                        </tr>
                        <tr>
                            <td>Nama Rekening</td>
                            <td>{{$account_holder_name}}</td>
                            <td>Nomor Rekening</td>
                            <td>{{$account_number}}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p>Jika Anda tidak melakukan permintaan penarikan ini, harap segera ubah kata sandi Anda dan hubungi customer service kami di <a class="link" href="https://api.whatsapp.com/send?phone=6281211119655&text=&source=&data=" target="_blank" style="font-weight: bold">+62 812-1111-9655</a>
                            atau <span class="email">info@gomodo.tech</span>
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    Terima kasih telah menggunakan mygomodo.com
                    <br>Powered by Gomodo
                </p>
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>




