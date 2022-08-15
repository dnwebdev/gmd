<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        html, body {
            background-color: #f6f6f6;
            font-family: "Montserrat", sans-serif;
        }
        table.main{
            padding: 15px;
        }


        .j-text {
            text-align: justify;
        }
        .r-text{
            text-align: right;
        }
        .bold{
            font-weight: bold;
        }

        td.m3 {
            height: 15px;
        }

        img#logo {
            width: 60px;
        }
        table.main table {
            width: 100%;
        }
        table.bordered {
            border: 1px solid #f6f6f6;
            padding: 15px;
        }
        table img {
            max-width: 100%;
            height: auto;
        }

        @media all {
            table.main {
                width: 320px;
                margin: 0 auto;
                background-color: #fff;
                font-size: smaller;
            }
            table.main tbody > tr.p15 > td {
                padding: 5px;
            }

        }
        @media all and (min-width: 767px){
            table.main {
                width: 640px;
                margin: 0 auto;
                background-color: #fff;
                font-size: smaller;
            }
            table.main tbody > tr.p15 > td {
                padding: 5px;
            }

        }
    </style>
</head>
<body>
<table class="main">
    <tr>
        <td>
            <table>
                <tr>
                    <td><img id="logo" src="{{asset('landing/img/Logo.png')}}" alt=""></td>
                    <td class="r-text"><h3>PT Gomodo Technologies</h3></td>
                </tr>
            </table>
        </td>

    </tr>
    <tr>
        <td>

        </td>

    </tr>
    <tr class="p15">
        <td>
            Hallo Provider
        </td>
    </tr>
    <tr class="p15">
        <td>
            Anda mendapatkan permintaan pembayaran cicilan dari customer.
            Berikut rinciannya :
        </td>
    </tr>
    <tr>
        <td class="m3">

        </td>

    </tr>
    <tr class="p15">
        <td>
            <table class="bordered">
                <tr>
                    <td>No Invoice</td>
                    <td>:</td>
                    <td class="bold">INV123454623754</td>
                </tr>

                <tr>
                    <td>Total Harga</td>
                    <td>:</td>
                    <td>IDR 100.000</td>
                </tr>
                <tr>
                    <td>Jumlah Cicilan</td>
                    <td>:</td>
                    <td>3</td>
                </tr>
                <tr>
                    <td>Metode pembayaran</td>
                    <td>:</td>
                    <td>Virtual Account</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr class="p15">
        <td>
            <p class="j-text">
                Email ini dibuat secara otomatis oleh sistem. Harap tidak membalas email ini. Jika ada pertanyaan,
                hubungi kami melalui nomor whatsapp di nomor +62812-1111-9655
            </p>
            <p>
            <hr>
            </p>
        </td>
    </tr>
    <tr>
        <td>

        </td>

    </tr>
    <tr class="p15">
        <td>
            Helo Provider
        </td>
    </tr>
    <tr class="p15">
        <td>
            Anda mendapatkan permintaan pembayaran cicilan dari customer.
            Berikut rinciannya :
        </td>
    </tr>
    <tr>
        <td class="m3">

        </td>

    </tr>
    <tr class="p15">
        <td>
            <table class="bordered">
                <tr>
                    <td>No Invoice</td>
                    <td>:</td>
                    <td class="bold">INV123454623754</td>
                </tr>

                <tr>
                    <td>Total Harga</td>
                    <td>:</td>
                    <td>IDR 100.000</td>
                </tr>
                <tr>
                    <td>Jumlah Cicilan</td>
                    <td>:</td>
                    <td>3</td>
                </tr>
                <tr>
                    <td>Metode pembayaran</td>
                    <td>:</td>
                    <td>Virtual Account</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr class="p15">
        <td>
            <p class="j-text">
                Email ini dibuat secara otomatis oleh sistem. Harap tidak membalas email ini. Jika ada pertanyaan,
                hubungi kami melalui nomor whatsapp di nomor +62812-1111-9655
            </p>
            <p>
            <hr>
            </p>
        </td>
    </tr>
    <tr class="p15">
        <td>
            <h3>Powered By GOMODO</h3>
        </td>
    </tr>

</table>
</body>
</html>