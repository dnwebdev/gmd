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
                        Hello <span style="font-weight: bold;">{{ucwords($user->first_name.' '.$user->last_name)}}</span>
                    </td>
                </tr>
                <tr style="padding: 5px;">
                    <td>
                        You've got a request to change your bank account, this is the detail :

                    </td>
                </tr>
                <tr>
                    <td style="min-height: 15px;padding: 5px;">

                    </td>

                </tr>
                <tr style="padding: 5px;">
                    <td>
                        <table style="border: 1px solid #f6f6f6;padding: 15px;">
                            <tr>
                                <td>Bank</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{$bankName}}</td>
                            </tr>

                            <tr>
                                <td>Bank Account Name</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{$bank->bank_account_name}}</td>
                            </tr>
                            <tr>
                                <td>Bank Account Number</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{$bank->bank_account_number}}</td>
                            </tr>
                            <tr>
                                <td>Bank Document</td>
                                <td>:</td>
                                <td><img width="100"
                                         src="{{asset('uploads/bank_document/'.$bank->bank_account_document)}}" alt="" style="max-width: 100%;height: auto;">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr style="padding: 5px;">
                    <td>
                        <p style="text-align: justify;">
                            This email is created automatically by the system. Please do not reply to this email. If
                            there are questions, contact us via whatsapp number at +62812-1111-9655
                        </p>
                        <hr>
                    </td>
                </tr>
                <tr style="padding: 5px;">
                    <td style="text-align: center">
                        <a href="{{request()->isSecure()?'https://':'http://'}}{{$user->company->domain_memoria.'/change-bank-request?u='.$bank->id.'&t='.$bank->token.'&action=reject'}}"
                           style="background-color: #ff4444;border-radius: 5px;padding: 8px 12px;color: white;text-decoration: none;">Reject</a>
                        <a href="{{request()->isSecure()?'https://':'http://'}}{{$user->company->domain_memoria.'/change-bank-request?u='.$bank->id.'&t='.$bank->token.'&action=approve'}}"
                           style="background-color: #40d07d;border-radius: 5px;padding: 8px 12px;color: white;text-decoration: none;">Approve</a>
                    </td>

                </tr>
                <tr>
                    <td style="min-height: 15px;padding: 5px;">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td style="height: 3rem;;padding: 5px;">
                    </td>
                </tr>
                <tr style="padding: 5px;">
                    <td>
                        Hallo <span style="font-weight: bold;">{{ucwords($user->first_name.' '.$user->last_name)}}</span>
                    </td>
                </tr>
                <tr style="padding: 5px;">
                    <td>
                        Anda mendapatkan permintaan pergantian Informasi Bank Account Anda
                        Berikut rinciannya :
                    </td>
                </tr>
                <tr>
                    <td style="min-height: 15px;padding: 5px;">

                    </td>

                </tr>
                <tr style="padding: 5px;">
                    <td>
                        <table style="border: 1px solid #f6f6f6;padding: 15px;">
                            <tr>
                                <td>Bank</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{$bankName}}</td>
                            </tr>

                            <tr>
                                <td>Nama Pemilik Rekening</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{$bank->bank_account_name}}</td>
                            </tr>
                            <tr>
                                <td>Nomor Rekening</td>
                                <td>:</td>
                                <td style="font-weight: bold;">{{$bank->bank_account_number}}</td>
                            </tr>
                            <tr>
                                <td>Dokumen Bank</td>
                                <td>:</td>
                                <td><img width="100"
                                         src="{{asset('uploads/bank_document/'.$bank->bank_account_document)}}" alt="" style="max-width: 100%;height: auto;">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr style="padding: 5px;">
                    <td>
                        <p style="text-align: justify;">
                            Email ini dibuat secara otomatis oleh sistem. Harap tidak membalas email ini. Jika ada
                            pertanyaan,
                            hubungi kami melalui nomor whatsapp di nomor +62812-1111-9655
                        </p>
                        <p>
                        <hr>
                        </p>
                    </td>
                </tr>
                <tr style="padding: 5px;">
                    <td style="text-align: center">
                        <a href="{{request()->isSecure()?'https://':'http://'}}{{$user->company->domain_memoria.'/change-bank-request?u='.$bank->id.'&t='.$bank->token.'&action=reject'}}" style="background-color: #ff4444;border-radius: 5px;padding: 8px 12px;color: white;text-decoration: none;">Tolak</a>
                        <a href="{{request()->isSecure()?'https://':'http://'}}{{$user->company->domain_memoria.'/change-bank-request?u='.$bank->id.'&t='.$bank->token.'&action=approve'}}" style="background-color: #40d07d;border-radius: 5px;padding: 8px 12px;color: white;text-decoration: none;">Setujui</a>
                    </td>

                </tr>
                <tr>
                    <td style="min-height: 15px;padding: 5px;">
                        <hr>
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



{{--<!doctype html>--}}
{{--<html lang="en">--}}
{{--<head>--}}
{{--    <meta charset="UTF-8">--}}
{{--    <meta name="viewport"--}}
{{--          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">--}}
{{--    <meta http-equiv="X-UA-Compatible" content="ie=edge">--}}
{{--    <title>Document</title>--}}
{{--    <style>--}}
{{--        html, body {--}}

{{--        }--}}

{{--        table.content {--}}
{{--        }--}}

{{--        table.main table {--}}
{{--            width: 100%;--}}
{{--        }--}}

{{--        @media all {--}}
{{--            table.main {--}}
{{--                width: 320px;--}}
{{--                margin: 0 auto;--}}
{{--                background-color: #fff;--}}
{{--                font-size: smaller;--}}
{{--            }--}}

{{--            table.main tbody > tr.p15 > td {--}}
{{--                padding: 5px;--}}
{{--            }--}}

{{--        }--}}

{{--        @media all and (min-width: 767px) {--}}
{{--            table.main {--}}
{{--                width: 640px;--}}
{{--                margin: 0 auto;--}}
{{--                background-color: #fff;--}}
{{--                font-size: smaller;--}}
{{--            }--}}

{{--            table.main tbody > tr.p15 > td {--}}
{{--                padding: 5px;--}}
{{--            }--}}

{{--        }--}}

{{--        a, a:focus, a:visited, a:active, a:hover {--}}
{{--            text-decoration: none;--}}
{{--        }--}}

{{--        .btn {--}}
{{--            border-radius: 5px;--}}
{{--            padding: 8px 12px;--}}
{{--            color: white;--}}
{{--        }--}}

{{--        .btn.btn-success {--}}
{{--            background-color: #40d07d;--}}
{{--        }--}}

{{--        .btn.btn-danger {--}}
{{--            background-color: #ff4444;--}}
{{--        }--}}

{{--        .btn.btn-primary {--}}
{{--            background-color: #4285f4;--}}
{{--        }--}}

{{--        .bg-gomodo {--}}
{{--            background-color: #4285f4;--}}
{{--        }--}}

{{--        .tbl-header {--}}
{{--            padding: 0 15px 0px 15px--}}
{{--        }--}}

{{--        .text-white {--}}
{{--            color: white;--}}
{{--        }--}}
{{--    </style>--}}
{{--</head>--}}
{{--<body style="background-color: #f6f6f6;font-family: Montserrat, sans-serif;">--}}
{{--<table>--}}
{{--    <tr class="bg-gomodo">--}}
{{--        <td>--}}
{{--            <table class="tbl-header" style="width: 100%">--}}
{{--                <tr>--}}
{{--                    <td><img style="width:48px!important;max-width: 100%;height: auto;" src="{{asset('landing/img/Logo.png')}}" alt=""></td>--}}
{{--                    <td class="text-white" style="text-align: right;"><h3>Gomodo Technologies</h3></td>--}}
{{--                </tr>--}}
{{--            </table>--}}
{{--        </td>--}}

{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td>--}}
{{--            <table class="content" style="padding: 15px;border: none;border-spacing: 0;">--}}
{{--                <tr class="p15">--}}
{{--                    <td>--}}
{{--                        Hello <span style="font-weight: bold;">{{ucwords($user->first_name.' '.$user->last_name)}}</span>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--                <tr class="p15">--}}
{{--                    <td>--}}
{{--                        You've got a request to change your bank account, this is the detail :--}}

{{--                    </td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                    <td style="min-height: 15px;padding: 5px;">--}}

{{--                    </td>--}}

{{--                </tr>--}}
{{--                <tr class="p15">--}}
{{--                    <td>--}}
{{--                        <table style="border: 1px solid #f6f6f6;padding: 15px;">--}}
{{--                            <tr>--}}
{{--                                <td>Bank</td>--}}
{{--                                <td>:</td>--}}
{{--                                <td style="font-weight: bold;">{{$bankName}}</td>--}}
{{--                            </tr>--}}

{{--                            <tr>--}}
{{--                                <td>Bank Account Name</td>--}}
{{--                                <td>:</td>--}}
{{--                                <td style="font-weight: bold;">{{$bank->bank_account_name}}</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Bank Account Number</td>--}}
{{--                                <td>:</td>--}}
{{--                                <td style="font-weight: bold;">{{$bank->bank_account_number}}</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Bank Document</td>--}}
{{--                                <td>:</td>--}}
{{--                                <td><img width="100"--}}
{{--                                         src="{{asset('uploads/bank_document/'.$bank->bank_account_document)}}" alt="" style="max-width: 100%;height: auto;">--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        </table>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--                <tr class="p15">--}}
{{--                    <td>--}}
{{--                        <p style="text-align: justify;">--}}
{{--                            This email is created automatically by the system. Please do not reply to this email. If--}}
{{--                            there are questions, contact us via whatsapp number at +62812-1111-9655--}}
{{--                        </p>--}}
{{--                        <p>--}}
{{--                        <hr>--}}
{{--                        </p>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--                <tr class="p15">--}}
{{--                    <td style="text-align: center">--}}
{{--                        <a href="{{request()->isSecure()?'https://':'http://'}}{{$user->company->domain_memoria.'/change-bank-request?u='.$bank->id.'&t='.$bank->token.'&action=reject'}}"--}}
{{--                           class="btn btn-danger">Reject</a>--}}
{{--                        <a href="{{request()->isSecure()?'https://':'http://'}}{{$user->company->domain_memoria.'/change-bank-request?u='.$bank->id.'&t='.$bank->token.'&action=approve'}}"--}}
{{--                           style="background-color: #40d07d;">Approve</a>--}}
{{--                    </td>--}}

{{--                </tr>--}}
{{--                <tr>--}}
{{--                    <td style="min-height: 15px;padding: 5px;">--}}
{{--                        <hr>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--                <tr class="p15">--}}
{{--                    <td>--}}
{{--                        Hallo <span style="font-weight: bold;">{{ucwords($user->first_name.' '.$user->last_name)}}</span>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--                <tr class="p15">--}}
{{--                    <td>--}}
{{--                        Anda mendapatkan permintaan pergantian Informasi Bank Account Anda--}}
{{--                        Berikut rinciannya :--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                    <td style="min-height: 15px;padding: 5px;">--}}

{{--                    </td>--}}

{{--                </tr>--}}
{{--                <tr class="p15">--}}
{{--                    <td>--}}
{{--                        <table style="border: 1px solid #f6f6f6;padding: 15px;">--}}
{{--                            <tr>--}}
{{--                                <td>Bank</td>--}}
{{--                                <td>:</td>--}}
{{--                                <td style="font-weight: bold;">{{$bankName}}</td>--}}
{{--                            </tr>--}}

{{--                            <tr>--}}
{{--                                <td>Nama Pemilik Rekening</td>--}}
{{--                                <td>:</td>--}}
{{--                                <td style="font-weight: bold;">{{$bank->bank_account_name}}</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Nomor Rekening</td>--}}
{{--                                <td>:</td>--}}
{{--                                <td style="font-weight: bold;">{{$bank->bank_account_number}}</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Dokumen Bank</td>--}}
{{--                                <td>:</td>--}}
{{--                                <td><img width="100"--}}
{{--                                         src="{{asset('uploads/bank_document/'.$bank->bank_account_document)}}" alt="" style="max-width: 100%;height: auto;">--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        </table>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--                <tr class="p15">--}}
{{--                    <td>--}}
{{--                        <p style="text-align: justify;">--}}
{{--                            Email ini dibuat secara otomatis oleh sistem. Harap tidak membalas email ini. Jika ada--}}
{{--                            pertanyaan,--}}
{{--                            hubungi kami melalui nomor whatsapp di nomor +62812-1111-9655--}}
{{--                        </p>--}}
{{--                        <p>--}}
{{--                        <hr>--}}
{{--                        </p>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--                <tr class="p15">--}}
{{--                    <td style="text-align: center">--}}
{{--                        <a href="{{request()->isSecure()?'https://':'http://'}}{{$user->company->domain_memoria.'/change-bank-request?u='.$bank->id.'&t='.$bank->token.'&action=reject'}}"--}}
{{--                           class="btn btn-danger">Tolak</a>--}}
{{--                        <a href="{{request()->isSecure()?'https://':'http://'}}{{$user->company->domain_memoria.'/change-bank-request?u='.$bank->id.'&t='.$bank->token.'&action=approve'}}"--}}
{{--                           style="background-color: #40d07d;">Setujui</a>--}}
{{--                    </td>--}}

{{--                </tr>--}}
{{--                <tr>--}}
{{--                    <td style="min-height: 15px;padding: 5px;">--}}
{{--                        <hr>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--                <tr class="p15">--}}
{{--                    <td>--}}
{{--                        <h3>Powered By GOMODO</h3>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--            </table>--}}
{{--        </td>--}}

{{--    </tr>--}}


{{--</table>--}}
{{--</body>--}}
{{--</html>--}}
