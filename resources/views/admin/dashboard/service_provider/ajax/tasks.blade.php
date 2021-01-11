<div class="table-responsive">
    <table class="table m-0">
        <thead>
            <tr>
                <th>Contract Code</th>
                <th>Work Order Title</th>
                <th>Service Provider</th>
                <th>Property</th>
                <th>Task Title</th>
                <th>Task Complete(%)</th>
                <!-- <th>Status</th> -->
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $task)
            <tr>
               <td>{{@$task->contract->code}}</td> 
               <td>{{@$task->work_order->task_title}}</td> 
               <td>{{@$task->contract->service_provider->name}}</td>
               <td>{{@$task->property->property_name}}</td>
               <td>{{@$task->task_title}}</td>
               <td>@if(@$task->task_complete_percent>0){{@$task->task_complete_percent}}@else{{'Not Started Yet'}}@endif</td>
               <!-- <td>
                {{$task->get_status_name()}}
               </td> -->
            </tr>
            @empty
            <tr>
               <td colspan="7">No upcomming tasks</td> 
            </tr>
            @endforelse
        </tbody>
    </table>
</div>