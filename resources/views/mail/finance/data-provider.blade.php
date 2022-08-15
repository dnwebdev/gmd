<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-size: 14px;color: rgba(0, 0, 0, 0.54);">
    <table cellpadding="5" cellspacing="0" style="border-collapse:collapse;width: 680px;margin: auto;">
        <tr>
            <td colspan="3">
                <table style="border-collapse:collapse;width: 100%;">
                    <tr>
                        <td colspan="2" class="provider-logo">
                            <img src="{{ public_path('landing/img/Logo.png') }}" height="50" alt="Logo" />
                        </td>
                        <td style="width: 25%;">
                            <h4 style="color:rgb(4,155,248); float: right;">Gomodo Technologies</h4>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p style="color: rgb(59,70,80);font-weight: bold;">Hello Team,</p>
                <p>
                    Berikut ini adalah data-data provider {{ $finance->company->company_name }}
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table align="center" cellpadding="5" cellspacing="0" width="680px">
                    <tbody>
                        <tr>
                            <td>Company Name :</td>
                            <td>{{ $finance->company->company_name }}</td>
                            <td>Phone Number :</td>
                            <td>{{ $finance->company->phone_company ? $finance->company->phone_company : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Email :</td>
                            <td>{{ $finance->company->email_company ? $finance->company->email_company : '-' }}</td>
                            <td>Ownership Status :</td>
                            <td>{{ $finance->company->ownership_status }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding-top: 5%">
                <label class="caption" for="">FINANCE DETAIL</label>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table cellpadding="5" cellspacing="0" style="border-collapse:collapse;width: 680px;margin: auto;">
                    <tbody>
                        <tr>
                            <td>Nama Finance :</td>
                            <td>{{ $finance->typeFinance->categoryType->name_finance }}</td>
                            <td>Jangka Waktu Pinjam :</td>
                            <td>{{ $finance->timeFinance->name_time_id }}</td>
                        </tr>
                        <tr>
                            <td>Tipe Finance :</td>
                            <td>{{ $finance->typeFinance->title_id }}</td>
                            <td>Amount :</td>
                            <td>Rp. {{ number_format($finance->amount,0) }}</td>
                        </tr>
                        <tr>
                            <td>Min Suku Bunga :</td>
                            <td>1.5 * {{ $finance->timeFinance->duration_time }} = {{$finance->min_suku_bunga}} per bulan</td>
                            <td>Max Suku Bunga :</td>
                            <td>2 * {{ $finance->timeFinance->duration_time }} = {{$finance->max_suku_bunga}} per bulan</td>
                        </tr>
                        <tr>
                            <td>Biaya Provisi :</td>
                            <td>Rp. {{ number_format($finance->fee_provisi,0) }}</td>
                            <td>Biaya Denda Keterlambatan :</td>
                            <td>Rp. {{ number_format($finance->fee_penalty_delay,0) }}</td>
                        </tr>
                        <tr>
                            <td>Biaya Pelunasan Tercepat :</td>
                            <td>Rp. {{ number_format($finance->fee_settled,0) }}</td>
                            <td>Biaya Administrasi :</td>
                            <td>Rp. {{ number_format($finance->fee_admin,0) }}</td>
                        </tr>
                        <tr>
                            <td>Biaya Asuransi</td>
                            <td>Rp. {{ number_format($finance->fee_insurance,0) }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        {{-- <tr>
            <td colspan="3" style="text-align: center;">
                <p>
                    <a target="_blank" href="http://{{env('APP_URL')}}/back-office/finance/{{ $finance->id }}/detail"
                        style="text-decoration: none;">Check
                        Detail</a>
                </p>
            </td>
        </tr> --}}
    </table>
</body>

</html>