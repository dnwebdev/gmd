<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            color: rgba(0, 0, 0, 0.54);
        }

        table {
            border-collapse: collapse
        }

        .details {
            height: 100px;
            width: 100%;
        }

        .details th, .details td {
            background-color: rgb(239, 247, 254);
        }

        .caption {
            color: rgb(59, 70, 80);
            font-weight: bold;
        }

        .details th, .details td {
            padding: 10px;
        }

        .notes {
            font-size: 11px;
            line-height: 1.2rem;
        }

        .total {
            text-align: right;
        }

        .discount {
            color: green;
            /* color: rgb(4,155,248) */
        }

        .details tr:nth-child(even) {
            background-color: rgb(239, 247, 254);
        }

        .details tr:nth-child(odd) {
            background-color: rgb(254, 254, 255);
        }

        .padding td {
            padding: 5px 0;
        }

        .details .first {
            padding-left: 3%;
            width: 20%;
        }

        .details .second {
            padding-left: 3%;
            width: 30%
        }

        .details .third {
            width: 50%;
        }

        .details .third span {
            /* text-align: right; */
        }

        .link {
            text-decoration: none;
            color: rgba(0, 0, 0, 0.54);
        }

        .email {
            font-weight: bold;
            text-decoration: underline;
            color: #b51fb5;
        }

        .faq {
            font-style: italic;
            text-align: center;
        }

        .faq-link {
            text-decoration: underline;
            color: #b51fb5;
            font-weight: bold;
        }

        .btn {
            padding: 10px;
            border-radius: 5px;
            color: blueviolet;
            text-decoration: none;
        }

    </style>
</head>
<body>

{{--Language English--}}
<table align="center" width="680px" cellpadding="5" cellspacing="0">
    <tbody>
    <tr>
        <td colspan="3">
            <table width="100%">
                <tr>
                    <td colspan="2" class="provider-logo">
                        <img src="{{asset('landing/img/Logo-Gomodo.png')}}" height="50" alt="Logo Gomodo">
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="3">
            <p class="caption">Hello Admin Gomodo,</p>
            <p style="text-align: justify">
                {{$company_name}} is requesting bank account verification with the following details:
            </p>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <table align="center" cellpadding="5" cellspacing="0" width="680px">
                <tbody>
                <tr>
                    <td>Company Name</td>
                    <td>{{$company_name}}</td>
                    <td>Company Email</td>
                    <td>{{$company_email}}</td>
                </tr>
                <tr>
                    <td>Bank</td>
                    <td>{{$bank_name}}</td>
                    <td>Bank Account Name</td>
                    <td>{{$bank_account_name}}</td>
                </tr>
                <tr>
                    <td>Bank Account Number</td>
                    <td>{{$bank_account_number}}</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" style=" padding-top: 5%">
            <label class="caption" for="">Bank Document</label>
        </td>
    </tr>
    @if ($bank_document)
        <tr>
            <td colspan="3" width="50%">
                <img src="{{ asset($bank_document) }}"/>
            </td>
        </tr>
    @endif

    </tbody>
</table>
<hr>
<br>
{{--Language Indonesia--}}
<table align="center" width="680px" cellpadding="5" cellspacing="0">
    <tbody>
    <tr>
        <td colspan="3">
            <table width="100%">
                <tr>
                    <td colspan="2" class="provider-logo">
                        <img src="{{asset('landing/img/Logo-Gomodo.png')}}" height="50" alt="Logo Gomodo">
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="3">
            <p class="caption">Halo Admin Gomodo,</p>
            <p style="text-align: justify">
                {{$company_name}} meminta verifikasi rekening bank dengan rincian sebagai berikut:
            </p>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <table align="center" cellpadding="5" cellspacing="0" width="680px">
                <tbody>
                <tr>
                    <td>Nama Perusahaan</td>
                    <td>{{$company_name}}</td>
                    <td>Email Perusahaan</td>
                    <td>{{$company_email}}</td>
                </tr>
                <tr>
                    <td>Bank</td>
                    <td>{{$bank_name}}</td>
                    <td>Nama Bank Rekening</td>
                    <td>{{$bank_account_name}}</td>
                </tr>
                <tr>
                    <td>Nomor Rekenin Bank</td>
                    <td>{{$bank_account_number}}</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" style=" padding-top: 5%">
            <label class="caption" for="">Bank Document</label>
        </td>
    </tr>
    @if ($bank_document)
        <tr>
            <td colspan="3" width="50%">
                <img src="{{ asset($bank_document) }}"/>
            </td>
        </tr>
    @endif

    </tbody>
</table>
</body>
</html>
