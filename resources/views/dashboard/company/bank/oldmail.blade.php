<table style="border: 1px solid #cdcdcd; width: 640px; margin: auto; font-size: 12px; color: #1e2731; line-height: 20px;" cellspacing="0" cellpadding="0">
   <tbody>
      <tr>
         <td style="background-color: #ecf0f1; height: 55px; padding: 30px 0;" colspan="3" align="center"><a href="https://mygomodo.com" target="_blank" rel="noopener" data-saferedirecturl="https://mygomodo.com"><img src="{{ asset('landing-page/assets/images/logo.png') }}" /></a></td>
      </tr>
      <tr style="height: 30px;">
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td width="20">&nbsp;</td>
         <td style="line-height: 40px;">Hello Gomodo,<br />{{$company_name}} is requesting bank account verification with the following details:</td>
         <td width="20">&nbsp;</td>
      </tr>
      <tr>
         <td width="20">&nbsp;</td>
         <td style="line-height: 40px;">
            <strong style="box-sizing: border-box;">Bank Account Details</strong>
            <table style="box-sizing: border-box; border-collapse: collapse; font-size: 14px;" width="100%">
               <tbody>
                  <tr style="border-top: 1px solid #aeaeae; border-bottom: 1px solid #aeaeae;">
                     <td style="box-sizing: border-box; border-right: 1px solid #aeaeae; text-align: left; padding: 10px; background-color: #f9f9f9; vertical-align: top;">Company Name</td>
                     <td style="box-sizing: border-box; text-align: left; padding: 10px;">{{$company_name}}</td>
                  </tr>
                  <tr style="border-top: 1px solid #aeaeae; border-bottom: 1px solid #aeaeae;">
                     <td style="box-sizing: border-box; border-right: 1px solid #aeaeae; text-align: left; padding: 10px; background-color: #f9f9f9; vertical-align: top;">Company Email</td>
                     <td style="box-sizing: border-box; text-align: left; padding: 10px;">{{$company_email}}</td>
                  </tr>
                  <tr style="border-top: 1px solid #aeaeae; border-bottom: 1px solid #aeaeae;">
                     <td style="box-sizing: border-box; border-right: 1px solid #aeaeae; text-align: left; padding: 10px; background-color: #f9f9f9; vertical-align: top;">Bank</td>
                     <td style="box-sizing: border-box; text-align: left; padding: 10px;">{{$bank_name}}</td>
                  </tr>
                  <tr style="border-bottom: 1px solid #aeaeae;">
                     <td style="box-sizing: border-box; border-right: 1px solid #aeaeae; text-align: left; padding: 10px; background-color: #f9f9f9; vertical-align: top;">Bank Account Name</td>
                     <td style="box-sizing: border-box; text-align: left; padding: 10px;">{{$bank_account_name}}</td>
                  </tr>
                  <tr style="border-bottom: 1px solid #aeaeae;">
                     <td style="box-sizing: border-box; border-right: 1px solid #aeaeae; text-align: left; padding: 10px; background-color: #f9f9f9; vertical-align: top;">Bank Account Number</td>
                     <td style="box-sizing: border-box; text-align: left; padding: 10px;">{{$bank_account_number}}</td>
                  </tr>
                  <tr style="border-bottom: 1px solid #aeaeae;">
                     <td style="box-sizing: border-box; border-right: 1px solid #aeaeae; text-align: left; padding: 10px; background-color: #f9f9f9; vertical-align: top;" width="30%">Bank Document</td>
                     <td style="box-sizing: border-box; text-align: left; padding: 10px;" width="70%"><img src="{{ asset($bank_document) }}" /></td>
                  </tr>
               </tbody>
            </table>
         </td>
         <td width="20">&nbsp;</td>
      </tr>
      <tr>
         <td width="20">&nbsp;</td>
         <td style="line-height: 40px;">Please do a verification immediately.</td>
         <td width="20">&nbsp;</td>
      </tr>
      <tr style="height: 20px;">
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td width="20">&nbsp;</td>
         <td>Sincerely,<br /> Gomodo Mail Bot<br /> <a href="https://mygomodo.com" target="_blank" rel="noopener" data-saferedirecturl="https://mygomodo.com">https://mygomodo.com</a></td>
         <td width="20">&nbsp;</td>
      </tr>
      <tr style="height: 50px;">
         <td>&nbsp;</td>
      </tr>
   </tbody>
</table>