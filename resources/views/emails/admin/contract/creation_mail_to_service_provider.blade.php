@extends('emails.layouts.main_layout')

@section('body')
<tr>
  <td align="left" valign="top" style="font-family: Arial; font-size: 14px; line-height: 20px; background:#eaf4d8; padding: 15px 10px; border:1px solid #666;">
  	{!!$mail_content!!}
   
  </td>
</tr>

@endsection