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
         <td style="line-height: 40px;">Hello {{$company_name}},<br /> {{$name}} is requesting special inquiry for your product <strong>{{$product_name}}</strong>.</td>
         <td width="20">&nbsp;</td>
      </tr>
      <tr>
         <td width="20">&nbsp;</td>
         <td style="line-height: 40px;">
            <strong style="box-sizing: border-box;">Special Inquiry Details</strong>
            <table style="box-sizing: border-box; border-collapse: collapse; font-size: 14px;" width="100%">
               <tbody>
                  <tr style="border-top: 1px solid #aeaeae; border-bottom: 1px solid #aeaeae;">
                     <td style="box-sizing: border-box; border-right: 1px solid #aeaeae; text-align: left; padding: 10px; background-color: #f9f9f9; vertical-align: top;">Booker Name</td>
                     <td style="box-sizing: border-box; text-align: left; padding: 10px;">{{$name}}</td>
                  </tr>
                  <tr style="border-bottom: 1px solid #aeaeae;">
                     <td style="box-sizing: border-box; border-right: 1px solid #aeaeae; text-align: left; padding: 10px; background-color: #f9f9f9; vertical-align: top;">Booker Email</td>
                     <td style="box-sizing: border-box; text-align: left; padding: 10px;">{{$email}}</td>
                  </tr>
                  <tr style="border-bottom: 1px solid #aeaeae;">
                     <td style="box-sizing: border-box; border-right: 1px solid #aeaeae; text-align: left; padding: 10px; background-color: #f9f9f9; vertical-align: top;">Booker Phone</td>
                     <td style="box-sizing: border-box; text-align: left; padding: 10px;">{{$phone}}</td>
                  </tr>
                  <tr style="border-bottom: 1px solid #aeaeae;">
                     <td style="box-sizing: border-box; border-right: 1px solid #aeaeae; text-align: left; padding: 10px; background-color: #f9f9f9; vertical-align: top;" width="30%">Product Name</td>
                     <td style="box-sizing: border-box; text-align: left; padding: 10px;" width="70%">{{$product_name}}</td>
                  </tr>
                  <tr style="border-bottom: 1px solid #aeaeae;">
                     <td style="box-sizing: border-box; border-right: 1px solid #aeaeae; text-align: left; padding: 10px; background-color: #f9f9f9; vertical-align: top;">Requested Date</td>
                     <td style="box-sizing: border-box; text-align: left; padding: 10px;">{{$schedule}}</td>
                  </tr>
                  <tr style="border-bottom: 1px solid #aeaeae;">
                     <td style="box-sizing: border-box; border-right: 1px solid #aeaeae; text-align: left; padding: 10px; background-color: #f9f9f9; vertical-align: top;">Total Guests</td>
                     <td style="box-sizing: border-box; text-align: left; padding: 10px;">{{$guest}}</td>
                  </tr>
                  @if($payment != '')
                  <tr style="border-bottom: 1px solid #aeaeae;">
                     <td style="box-sizing: border-box; border-right: 1px solid #aeaeae; text-align: left; padding: 10px; background-color: #f9f9f9; vertical-align: top;">Requested Payment Method</td>
                     <td style="box-sizing: border-box; text-align: left; padding: 10px;">{{$payment}}</td>
                  </tr>
                  @endif
                  @if($notes != '')
                  <tr style="border-bottom: 1px solid #aeaeae;">
                     <td style="box-sizing: border-box; border-right: 1px solid #aeaeae; text-align: left; padding: 10px; background-color: #f9f9f9; vertical-align: top;">Notes from Booker</td>
                     <td style="box-sizing: border-box; text-align: left; padding: 10px;">{{$notes}}</td>
                  </tr>
                  @endif
               </tbody>
            </table>
         </td>
         <td width="20">&nbsp;</td>
      </tr>
      <tr>
         <td width="20">&nbsp;</td>
         <td style="line-height: 40px;">We hope you respond the booker immediately.</td>
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