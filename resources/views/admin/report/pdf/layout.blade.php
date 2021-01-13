<html>
   <head>
      <meta charset='utf-8'>
      </meta>
      <title>REPORT</title>
      <style>
      /*table {page-break-inside: avoid;}*/
      </style>
   </head>
   <body style='padding:0; margin: 0; font-family: Arial; font-size: 14px; line-height: 20px;'>
      <table width='100%' border='0' cellspacing='0' cellpadding='0' style='margin: 0 auto 20px; border-collapse:collapse;'>
         <tr>
            <td align='center' valign='top' style='font-family: Arial; font-size: 14px; line-height: 20px; padding: 20px 0; border-bottom: 1px solid #ccc;'>
             <img src="{{asset('assets/dist/img/OSOOL_logo.png')}}" width='151' height='70' alt='' style='border: 0;' />
            </td>
         </tr>         
         <tr>
            <td align='center' valign='top' style='font-family: Arial; font-size: 25px; color:#153755; height: 20px; font-weight: bold; text-align: center;'></td>
         </tr> 
      </table>
       @yield('body')
      <table width='100%' border='0' cellspacing='0' cellpadding='0' style='margin: 0 auto 20px; border-collapse:collapse;'>
               <tr>
                  <td align='center' valign='top' style='font-family: Arial; font-size: 25px; color:#153755; height: 20px; font-weight: bold; text-align: center;'></td>
               </tr>
               
               <tr>
                  <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px;'>
                     Our Best,<br />
                     <strong>OSOOL Team</strong>
                  </td>
               </tr>
               <tr>
                  <td align='center' valign='top' style='height: 30px'></td>
               </tr>        
               <tr>
                  <td align='center' valign='top' style='font-family: Arial; color:#153755; height: 20px; font-weight: bold; text-align: center; border-top: 1px solid #ccc;'></td>
               </tr>
               <tr>
                  <td align='center' valign='top' style='font-family: Arial; font-size: 14px; color:#8d8d8d; line-height: 20px;'>Copyright &copy; {{date('Y')}} . All rights reserved.</td>
               </tr>
            </table>
   </body>
</html>