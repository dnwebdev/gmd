<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        body{
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            color: rgba(0, 0, 0, 0.54);
        }
        .caption {
            color: rgb(59,70,80);
            font-weight: bold;
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
                        <img src="{{asset('landing/img/Logo-Gomodo.png')}}" height="50" alt="Logo Gomodo">
                    </td>
                    <td style="width: 25%;"></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="3">
            <p class="caption">Halo {{ucwords($user->first_name)}} {{ucwords($user->last_name)}},</p>
            <p>
                {!! trans('email_bank_account.content',['account_number'=>$bank->bank_account_number]) !!}
            </p>
            <p>
                {!! trans('email_bank_account.footer') !!}
            </p>

        </td>
    </tr>
    </tbody>
</table>

</body>
</html>
