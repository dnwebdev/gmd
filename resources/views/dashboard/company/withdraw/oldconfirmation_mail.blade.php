<table style="border: 1px solid #cdcdcd; width: 640px; margin: auto; font-size: 12px; color: #1e2731; line-height: 20px;" cellspacing="0" cellpadding="0">
   <tbody>
      <tr>
{{--         <td style="background-color: #ecf0f1; height: 55px; padding: 30px 0;" colspan="3" align="center"><a href="https://mygomodo.com" target="_blank" rel="noopener" data-saferedirecturl="https://mygomodo.com"><img src="{{ asset('landing-page/assets/images/logo.png') }}" /></a></td>--}}
         <td style="background-color: #ecf0f1; height: 55px; padding: 30px 0;" colspan="3" align="center"><a href="https://mygomodo.com" target="_blank" rel="noopener" data-saferedirecturl="https://mygomodo.com"><img src="{{asset('landing/img/Logo-Gomodo.png')}}" /></a></td>
      </tr>
      <tr style="height: 30px;">
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td width="20">&nbsp;</td>
         <td style="line-height: 40px;">Hello {{$company_name}},<br />We receive&nbsp;<span class="il">withdrawal</span>&nbsp;request for <strong>IDR {{$amount}}</strong> to {{$bank_code}} with account number {{$account_number}}.</td>
         <td width="20">&nbsp;</td>
      </tr>
      <tr>
         <td width="20">&nbsp;</td>
         <td style="line-height: 40px;">If you did not make this&nbsp;<span class="il">withdrawal</span>&nbsp;request, please change your password immediately and contact&nbsp;<a href="{{ baseUrl('#contact') }}" target="_blank" rel="noopener">customer service</a>.</td>
         <td width="20">&nbsp;</td>
      </tr>
      <tr style="height: 20px;">
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td width="20">&nbsp;</td>
         <td>Sincerely,<br /> Gomodo Customer Service<br /> <a href="https://mygomodo.com" target="_blank" rel="noopener" data-saferedirecturl="https://mygomodo.com">https://mygomodo.com</a></td>
         <td width="20">&nbsp;</td>
      </tr>
      <tr style="height: 50px;">
         <td>&nbsp;</td>
      </tr>
   </tbody>
</table>
