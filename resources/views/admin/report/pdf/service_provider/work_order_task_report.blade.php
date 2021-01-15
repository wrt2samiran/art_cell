
@extends('admin.report.pdf.layout')

@section('body')
<table  width='100%' border='0' cellspacing='0' cellpadding='0' style='margin: 0 auto 20px; border-collapse:collapse;'>
 <tr>
    <td align='center' valign='top' style='font-family: Arial; font-size: 22px; color:#3381c3; line-height: 30px; font-weight: bold; text-align: center;'>Work Order Report</td>
 </tr>
 <tr>
    <td align='center' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; text-align: center;'>Report On : {{Carbon::now()->format('d/m/Y h:i A')}}</td>
 </tr>
 <tr>
    <td align='center' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; text-align: center;'>From Date: {{request()->from_date}} - To Date: {{request()->to_date}}</td>
 </tr>
 <tr>
    <td align='center' valign='top' style='font-family: Arial; font-size: 25px; color:#153755; height: 20px; font-weight: bold; text-align: center;'></td>
 </tr>
</table>
<table width="100%" style="border-collapse: collapse;">
    <tr>
     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; font-weight: bold; color:#824d9e; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>Task Id</td>   
     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; font-weight: bold; color:#824d9e; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>Task Details</td>
     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; font-weight: bold; color:#824d9e; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>Work Order Title</td>
     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; font-weight: bold; color:#824d9e; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>Contract Details</td>
     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; font-weight: bold; color:#824d9e; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>Property Details</td>
     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; font-weight: bold; color:#824d9e; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>Current Status</td>
     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; font-weight: bold; color:#824d9e; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>Created Date</td>


    </tr>

 @forelse($task_lists as $task_list)
 <tr>
    <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>{{$task_list->id}}</td>
    <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
       
        <strong>Task Title</strong><br>
        <span>{{@$task_list->task_title}}</span>
        <br>
        <strong>Task Completed Percent</strong><br>
        <span>{{@$task_list->task_complete_percent}}%</span>
        
     </td>

     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>{{@$task_list->work_order->task_title}}</td>

     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
       
        <strong>Contract Code</strong><br>
        <span>{{@$task_list->contract->code}}</span>
        <br>
        <strong>Contract Name</strong><br>
        <span>{{@$task_list->contract->title}}</span>
        
     </td>


    <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
        <strong>Property Code</strong><br>
        <span>{{$task_list->property->code}}</span>
        <br>
        <strong>Property Name</strong><br>
        <span>{{$task_list->property->property_name}}</span>
        <br>
        <strong>Property Owner Name</strong><br>
        <span>{{$task_list->property->owner_details->name}}</span>
        <br>
        <strong>Property Owner Email</strong><br>
        <span>{{$task_list->property->owner_details->email}}</span>
        <br>
        <strong>City</strong><br>
        <span>{{$task_list->property->city->name}}</span>
        <br>
        <strong>Location</strong><br>
        <span>{{$task_list->property->address}}</span>
        
     </td>
     
     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>{{@$task_list->get_status_name()}}</td>
     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>{{Carbon::parse(@$task_list->created_at)->format('d/m/Y')}}</td>

 </tr>
@empty
 <tr>
     <td colspan="4" align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
        No Records
     </td>
 </tr>
@endforelse

</table>

@endsection