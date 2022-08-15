<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body style="background-color: #f6f6f6;font-family: Montserrat, sans-serif;width: 680px; margin: auto;">
<table>
    <tr style="background-color: #4285f4;">
        <td>
            <table style="width: 100%; padding: 0 15px 0px 15px">
                <tr>
                    <td><img style="width:48px!important;max-width: 100%;height: auto;" src="{{asset('landing/img/Logo.png')}}" alt=""></td>
                    <td style="text-align: right;color: white;"><h3>Gomodo Technologies</h3></td>
                </tr>
            </table>
        </td>

    </tr>
    <tr>
        <td>
            <table class="content" style="padding: 15px;border: none;border-spacing: 0;">
                <tr style="padding: 5px;">
                    <td>
                        Hello <span style="font-weight: bold;">Team</span>
                    </td>
                </tr>
                <tr style="padding: 5px;">
                    <td>
                        Berikut ini adalah data-data provider {{ $company->company_name }}
                    </td>
                </tr>
                <tr>
                    <td style="min-height: 15px;padding: 5px;">
                    </td>

                </tr>
                <tr style="padding: 5px;">
                    <td>
                        <p style="text-align: justify;">
                            Finance : {{ $finance->typeFinance->categoryType->name_finance }} <br>
                            Jangka Waktu Pinjam : {{ $finance->timeFinance->name_time_id }} <br>
                            Tipe Finance : {{ $finance->typeFinance->title_id }} <br>
                            Amount : {{ number_format($finance->amount,0) }} <br>
{{--                            Suku Bunga : {{ $finance->min_suku_bunga }} - {{ $finance->max_suku_bunga }} per month <br>--}}
                            Suku Bunga : 1,5% - 2% per month <br>
                            Biaya Provisi : {{ $finance->fee_provisi }} <br>
                            Biaya Denda Keterlambatan : {{ $finance->fee_penalty_delay }} <br>
                            Biaya Pelunasan Tercepat : {{ $finance->fee_settled }} <br>
                            Biaya Administrasi : {{ $finance->fee_admin }} <br>
                            Biaya Asuransi : {{ $finance->fee_insurance }} <br>
                        </p>
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="padding-bottom: 2rem;text-align: center;">
                        <a target="_blank" href="http://{{env('APP_URL')}}/back-office/finance/{{ $finance->id }}/detail"
                            style="padding: 10px 100px;background-color: rgb(4,155,248);border-radius: 5px;color: white;font-size: 15px;position: relative;margin-top: 60px;text-decoration: none;">Check Detail</a>
                    </td>
                </tr>
                <tr style="padding: 5px;">
                    <td>
                        <h3>Powered By GOMODO</h3>
                    </td>
                </tr>
            </table>
        </td>

    </tr>


</table>
</body>
</html>