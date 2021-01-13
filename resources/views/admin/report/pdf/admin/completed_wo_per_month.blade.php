@extends('admin.report.pdf.layout')

@section('body')
<table  width='100%' border='0' cellspacing='0' cellpadding='0' style='margin: 0 auto 20px; border-collapse:collapse;'>
 <tr>
    <td align='center' valign='top' style='font-family: Arial; font-size: 22px; color:#3381c3; line-height: 30px; font-weight: bold; text-align: center;'>Completed Work Orders/Month Report</td>
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
     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; font-weight: bold; color:#824d9e; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>Month</td>
     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; font-weight: bold; color:#824d9e; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>Work Order ID</td>
     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; font-weight: bold; color:#824d9e; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>Contract Details</td>
     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; font-weight: bold; color:#824d9e; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>Property Details</td>
     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; font-weight: bold; color:#824d9e; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>Task Details</td>
     <td align='left' valign='top' style='font-family: Arial; font-size: 14px; font-weight: bold; color:#824d9e; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
         Status
     </td>

    </tr>

    @foreach($data as $month_year=>$record)
        @if(count($record['work_orders']))
            @foreach($record['work_orders'] as $key=> $work_order)
             <tr>
                <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
                    @if($key=='0')   
                    <strong>Month</strong><br>
                    <span>{{$month_year}}</span>
                    <br>
                    <strong>Date Range</strong><br>
                    <span>
                        {{Carbon::parse($record['effective_from'])->format('d/m/Y')}} To {{Carbon::parse($record['effective_to'])->format('d/m/Y')}}
                    </span>
                    <br>
                    <strong>Total Work Orders</strong><br>
                    <span>
                        {{$record['total_wo']}}
                    </span>
                    @endif
                    
                </td>
                <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
                    {{$work_order->id}}
                </td>
                <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
                    <strong>Contract Code</strong><br>
                    <span>{{$work_order->contract->code}}</span>
                    <br>
                    <strong>Contract Name</strong><br>
                    <span>{{$work_order->contract->title}}</span>
                    <br>
                    <strong>Service Provider Name</strong><br>
                    <span>{{$work_order->contract->service_provider->name}}</span>
                    <br>
                    <strong>Service Provider Email</strong><br>
                    <span>{{$work_order->contract->service_provider->email}}</span>
                 </td>

                <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
                    <strong>Property Code</strong><br>
                    <span>{{$work_order->contract->property->code}}</span>
                    <br>
                    <strong>Property Name</strong><br>
                    <span>{{$work_order->contract->property->property_name}}</span>
                    <br>
                    <strong>Property Owner Name</strong><br>
                    <span>{{$work_order->contract->property->owner_details->name}}</span>
                    <br>
                    <strong>Property Owner Email</strong><br>
                    <span>{{$work_order->contract->property->owner_details->email}}</span>
                    <br>
                    <strong>City</strong><br>
                    <span>{{$work_order->contract->property->city->name}}</span>
                    <br>
                    <strong>Location</strong><br>
                    <span>{{$work_order->contract->property->address}}</span>
                 </td>
                 <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>

                    <strong>Task Title</strong><br>
                    <span>{{$work_order->task_title}}</span>
                    <br>
                    <strong>Service Name</strong><br>
                    <span>{{$work_order->service->service_name}}</span>
                 </td>
                 <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
                    <strong>Status Name</strong><br>
                    <span>{{$work_order->get_status_name()}}</span>
                    <br>
                    <strong>Completed Date</strong><br>
                    <span>
                    @if($work_order->work_order_complete_date)
                    {{Carbon::parse($work_order->work_order_complete_date)->format('d/m/Y')}}
                    @else
                    N/A
                    @endif
                    </span>
                 </td>

             </tr>

            @endforeach
        @else
        
         <tr>
            <td align='left' valign='top' style='font-family: Arial; font-size: 14px; color:#606060; line-height: 20px; padding: 8px; border: 1px solid #ccc;'>
                      
                <strong>Month</strong><br>
                <span>{{$month_year}}</span>
                <br>
                <strong>Date Range</strong><br>
                <span>
                {{Carbon::parse($record['effective_from'])->format('d/m/Y')}} To {{Carbon::parse($record['effective_to'])->format('d/m/Y')}}
                </span>
                <br>
                <strong>Total Work Orders</strong><br>
                <span>
                    {{$record['total_wo']}}
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


@endforeach 


</table>

@endsection

