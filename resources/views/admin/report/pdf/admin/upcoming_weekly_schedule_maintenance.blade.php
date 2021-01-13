@extends('admin.report.pdf.layout')

@section('body')
<table  width='100%' border='0' cellspacing='0' cellpadding='0' style='margin: 0 auto 20px; border-collapse:collapse;'>
 <tr>
    <td align='center' valign='top' style='font-family: Arial; font-size: 22px; color:#3381c3; line-height: 30px; font-weight: bold; text-align: center;'>Upcoming Weekly Schedule Maintenance Report</td>
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
     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; font-weight: bold; color:#824d9e; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>Week Details</td>
     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; font-weight: bold; color:#824d9e; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>Service Date</td>
     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; font-weight: bold; color:#824d9e; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>Contract Details</td>
     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; font-weight: bold; color:#824d9e; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>Property Details</td>
     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; font-weight: bold; color:#824d9e; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>Task Details</td>
     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; font-weight: bold; color:#824d9e; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>Labour Assigned</td>

    </tr>

 @forelse($upcoming_weekly_services as $upcoming_weekly_service)

    @if(count($upcoming_weekly_service['upcoming_service_dates']))
        @php
        $index=0;
        @endphp

        @foreach($upcoming_weekly_service['upcoming_service_dates'] as $upcoming_service_date)

         <tr>
            <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
                        
                @if($index=='0')
                <strong>Week Number</strong><br>
                <span>{{$upcoming_weekly_service['week_number']}}</span>
                <br>
                <strong>Year</strong><br>
                <span>{{$upcoming_weekly_service['year']}}</span>
                <br>
                <strong>Date Range</strong><br>
                <span>

                    {{Carbon::parse($upcoming_weekly_service['effective_from'])->format('d/m/Y')}} To  {{Carbon::parse($upcoming_weekly_service['effective_to'])->format('d/m/Y')}}
                </span>
                
                @endif 

            </td>
            <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
                        
                <span>{{$upcoming_service_date->date}}</span>
            </td>
            <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
                <strong>Contract Code</strong><br>
                <span>{{$upcoming_service_date->contract->code}}</span>
                <br>
                <strong>Contract Name</strong><br>
                <span>{{$upcoming_service_date->contract->title}}</span>
                <br>
                <strong>Service Provider Name</strong><br>
                <span>{{$upcoming_service_date->contract->service_provider->name}}</span>
                <br>
                <strong>Service Provider Email</strong><br>
                <span>{{$upcoming_service_date->contract->service_provider->email}}</span>
             </td>

            <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
                <strong>Property Code</strong><br>
                <span>{{$upcoming_service_date->contract->property->code}}</span>
                <br>
                <strong>Property Name</strong><br>
                <span>{{$upcoming_service_date->contract->property->property_name}}</span>
                <br>
                <strong>Property Owner Name</strong><br>
                <span>{{$upcoming_service_date->contract->property->owner_details->name}}</span>
                <br>
                <strong>Property Owner Email</strong><br>
                <span>{{$upcoming_service_date->contract->property->owner_details->email}}</span>
                <br>
                <strong>City</strong><br>
                <span>{{$upcoming_service_date->contract->property->city->name}}</span>
                <br>
                <strong>Location</strong><br>
                <span>{{$upcoming_service_date->contract->property->address}}</span>
                
             </td>
             <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
                <strong>Task Title</strong><br>
                <span>{{$upcoming_service_date->task_title}}</span>
                <br>
                <strong>Service Name</strong><br>
                <span>{{$upcoming_service_date->service->service_name}}</span>
               
             </td>
             <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
                @if(count($upcoming_service_date->task_details_list))
                    @foreach($upcoming_service_date->task_details_list as $key=>$labour_task)
                        {{($key!='0')?',':''}} {{$labour_task->userDetails->name}}
                    @endforeach
                @else
                No Labour Assigned
                @endif
               
             </td>

         </tr>



        @php
        $index++;
        @endphp

        @endforeach

    @else
     <tr>
        <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
                  
            <strong>Week Number</strong><br>
            <span>{{$upcoming_weekly_service['week_number']}}</span>
            <br>
            <strong>Year</strong><br>
            <span>{{$upcoming_weekly_service['year']}}</span>
            <br>
            <strong>Date Range</strong><br>
            <span>
                {{Carbon::parse($upcoming_weekly_service['effective_from'])->format('d/m/Y')}} To  {{Carbon::parse($upcoming_weekly_service['effective_to'])->format('d/m/Y')}}
            </span>
            
        </td>
        <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
            N/A
        </td>
        <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
            N/A
         </td>

        <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
            N/A
         </td>
         <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
            N/A
         </td>
         <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
            N/A
         </td>

     </tr>
    @endif

@empty
 <tr>
     <td colspan="6" align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
        No Upcoming Schedule
     </td>
 </tr>
@endforelse

</table>

@endsection