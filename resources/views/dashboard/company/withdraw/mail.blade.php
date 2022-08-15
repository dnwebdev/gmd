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
                            <a href="https://mygomodo.com" target="_blank"><img src="{{asset('landing/img/Logo-Gomodo.png')}}" height="50" alt="Logo"/></a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="3">
                <p class="caption">Hello {{ $company->company_name }},</p>
                <p>
                    Your IDR {{ number_format($amount,0)}} withdraw has been {{$status}}. @if(isset($reason)){{$reason.'. '}} @endif. please login to view history withdrawal
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p>
                    Thank you for using mygomodo.com to facilitate your transaction.
                    <br>Powered by Gomodo
                </p>
            </td>
        </tr>

    </tbody>
</table>
<hr>
<br>
{{--Language Indonesia--}}
<table  align="center" width="680px" cellpadding="5" cellspacing="0">
    <tbody>
        <tr>
            <td colspan="3">
                <table width="100%">
                    <tr>
                        <td colspan="2" class="provider-logo">
                            <a href="https://gomodo.id" target="_blank"><img src="{{asset('landing/img/Logo-Gomodo.png')}}" height="50" alt="Logo"/></a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="3">
                <p class="caption">Hai {{ $company->company_name }},</p>
                <p>
                    Dana Anda sebesar IDR {{ number_format($amount,0)}} telah selesai. @if(isset($reason)){{$reason.'. '}} @endif. silahkan login untuk melihat riwayat penarikan
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p>
                    Terima kasih telah menggunakan mygomodo.com untuk memudahkan transaksi Anda.
                    <br>Powered by Gomodo
                </p>
            </td>
        </tr>

    </tbody>
</table>
</body>
</html>




