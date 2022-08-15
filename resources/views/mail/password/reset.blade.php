
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

        table.content {
            padding: 15px;
            border: none;
            border-spacing: 0;
        }


        .j-text {
            text-align: justify;
        }

        .r-text {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        td.m3 {
            min-height: 15px;
            padding: 5px;
        }

        img#logo {
            width: 48px;
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

        @media all and (min-width: 767px) {
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
        a,a:focus,a:visited,a:active,a:hover{
            text-decoration: none;
        }
        .btn{
            border-radius: 5px;
            padding: 8px 12px;
            color: white;
        }
        .btn.btn-success{
            background-color: #40d07d;
        }
        .btn.btn-danger{
            background-color: #ff4444;
        }
        .btn.btn-primary{
            background-color: #4285f4;
        }
        .bg-gomodo{
            background-color: #4285f4;
        }
        .tbl-header{
            padding: 0 15px 0px 15px
        }
        .text-white{
            color: white;
        }
    </style>
</head>
<body>
<table class="main">
    <tr class="bg-gomodo">
        <td>
            <table class=" tbl-header">
                <tr>
                    <td><img id="logo" style="width: 48px!important;" src="{{asset('landing/img/Logo.png')}}" alt=""></td>
                    <td class="r-text text-white"><h3>Gomodo Technologies</h3></td>
                </tr>
            </table>
        </td>

    </tr>
    <tr>
        <td>
            <table class="content">
                <tr class="p15">
                    <td>
                        Hello <span class="bold">{{$notifiable->first_name}}</span>
                    </td>
                </tr>
                <tr class="p15">
                    <td>
                        You are receiving this email because we received a password reset request for your account at <a href="{{env('APP_URL')}}">{{env('APP_URL')}}</a> :

                    </td>
                </tr>
                <tr>
                    <td class="m3">

                    </td>

                </tr>

                <tr class="p15">
                    <td>
                        <p class="j-text">
                            If you did not request a password reset, no further action is required.
                        </p>
                        <p>
                        <hr>
                        </p>
                    </td>
                </tr>
                <tr class="p15">
                    <td style="text-align: center">
                        <a href="{{$url}}" class="btn btn-primary" style="  border-radius: 5px;
            padding: 8px 12px;
            color: white;
            background-color: #4285f4;">Reset my password</a>
                    </td>

                </tr>
                <tr>
                    <td class="m3">
                        <hr>
                    </td>
                </tr>
                <tr class="p15">
                    <td>
                        Hallo <span class="bold">{{$notifiable->first_name}}</span>
                    </td>
                </tr>
                <tr class="p15">
                    <td>
                        Anda menerima email ini karena telah melakukan permohonan penggantian password di <a href="{{env('APP_URL')}}">{{env('APP_URL')}}</a> :

                    </td>
                </tr>
                <tr>
                    <td class="m3">

                    </td>

                </tr>
                <tr class="p15">
                    <td>
                        <p class="j-text">
                            Jika Anda merasa tidak pernah memohon pergantian kata sandi, silakan mengabaikan email ini, akun Anda akan tetap aman tanpa perubahan.
                        </p>
                        <p>
                        <hr>
                        </p>
                    </td>
                </tr>
                <tr class="p15">
                    <td style="text-align: center">
                        <a href="{{$url}}" class="btn btn-primary"
                           style="

            border-radius: 5px;
            padding: 8px 12px;
            color: white;
            background-color: #4285f4;
" >Ubah kata sandi</a>
                    </td>

                </tr>
                <tr>
                    <td class="m3">
                        <hr>
                    </td>
                </tr>
                <tr class="p15">
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

