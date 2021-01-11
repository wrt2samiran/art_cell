<!doctype html>
<html>
   <head>
      <meta charset="utf-8">
      <title>Email</title>
   </head>
   <body style="padding:0; margin: 0; font-family: Arial; font-size: 14px; line-height: 20px;">
   
     
      <table width="650" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto 20px; border-collapse:collapse;">         
        <tr>
            <td align="left" valign="top" style="font-family: Arial; font-size: 14px; line-height: 20px; font-weight:bold; text-transform: uppercase; background:#6e923d; padding: 15px 10px; border:1px solid #666;"><img src="{{asset('assets/dist/img/OSOOL_logo.png')}}" alt="" style="height: 50px;"></td>
         </tr>

          @yield('body')

        <tr>
          <td align="center" valign="top" style="font-family: Arial; font-size: 12px; color:#fff; line-height: 20px; background:#222; padding: 10px; border:1px solid #666;">Copyright © {{date('Y')}} | {{env('APP_NAME','OSOOL')}}. All Rights Reserved.</td>
        </tr>         
      </table>   
   
   </body>
</html>