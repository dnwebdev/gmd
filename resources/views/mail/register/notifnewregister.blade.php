
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
                        Hello <span class="bold">GOMODO TEAM</span>
                    </td>
                </tr>
                <tr class="p15">
                    <td>
                        You've got a new registered Provider, this is for the detail :

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
                                <td>Provider Name</td>
                                <td>:</td>
                                <td class="bold">{{$provider->company_name}}</td>
                            </tr>

                            <tr>
                                <td>Provider Website</td>
                                <td>:</td>
                                <td class="bold">{{$provider->domain_memoria}}</td>
                            </tr>
                            @if($provider->phone_company)
                                <tr>
                                    <td>Provider Phone Number</td>
                                    <td>:</td>
                                    <td class="bold">{{$provider->phone_company}}</td>
                                </tr>
                            @endif
                            @if (!empty($provider->agent->first_name))
                                <tr>
                                    <td>User Name</td>
                                    <td>:</td>
                                    <td class="bold">{{$provider->agent->first_name.' '.$provider->agent->last_name}}</td>
                                </tr>
                            @endif
                            @if (!empty($provider->agent->email))
                                <tr>
                                    <td>User Email</td>
                                    <td>:</td>
                                    <td class="bold">{{$provider->agent->email}}</td>
                                </tr>
                            @endif

                            @if(!empty($provider->agent->phone))
                                <tr>
                                    <td>User Phone Number</td>
                                    <td>:</td>
                                    <td class="bold">{{$provider->agent->phone}}</td>
                                </tr>
                            @endif
                        </table>
                    </td>
                </tr>
                <tr class="p15">
                    <td>
                        <p class="j-text">
                            This email is created automatically by the system. Please do not reply to this email. If there are questions, contact us via whatsapp number at +62812-1111-9655
                        </p>
                        <p>
                        <hr>
                        </p>
                    </td>
                </tr>
                <tr class="p15">
                    <td style="text-align: center">
                        <a href="{{request()->isSecure()?'https://':'http://'}}{{$provider->domain_memoria}}" class="btn btn-primary" style="  border-radius: 5px;
            padding: 8px 12px;
            color: white;
            background-color: #4285f4;">View Website</a>
                    </td>

                </tr>
                <tr>
                    <td class="m3">
                        <hr>
                    </td>
                </tr>
                <tr class="p15">
                    <td>
                        Hallo <span class="bold">GOMODO TEAM</span>
                    </td>
                </tr>
                <tr class="p15">
                    <td>
                        Kita mendapatkan teman provider baru, berikut adalah detail informasinya:

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
                                <td>Nama Provider</td>
                                <td>:</td>
                                <td class="bold">{{$provider->company_name}}</td>
                            </tr>

                            <tr>
                                <td>Website Provider</td>
                                <td>:</td>
                                <td class="bold">{{$provider->domain_memoria}}</td>
                            </tr>
                            @if(!empty($provider->agent->phone))
                                <tr>
                                    <td>Telp Perusahaan</td>
                                    <td>:</td>
                                    <td>{{$provider->agent->phone}}</td>
                                </tr>
                            @endif
                            @if(!empty($provider->agent->first_name))
                                <tr>
                                    <td>Nama Pengguna</td>
                                    <td>:</td>
                                    <td class="bold">{{$provider->agent->first_name.' '.$provider->agent->last_name}}</td>
                                </tr>
                            @endif
                            @if(!empty($provider->agent->email))
                                <tr>
                                    <td>Email pengguna</td>
                                    <td>:</td>
                                    <td class="bold">{{$provider->agent->email}}</td>
                                </tr>
                            @endif

                            @if(!empty($provider->agent->phone))
                                <tr>
                                    <td>Telp Pengguna</td>
                                    <td>:</td>
                                    <td class="bold">{{$provider->agent->phone}}</td>
                                </tr>
                            @endif
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
                    <td style="text-align: center">
                        <a href="{{request()->isSecure()?'https://':'http://'}}{{$provider->domain_memoria}}" class="btn btn-primary"
                           style="

            border-radius: 5px;
            padding: 8px 12px;
            color: white;
            background-color: #4285f4;
" >Lihat Website</a>
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

