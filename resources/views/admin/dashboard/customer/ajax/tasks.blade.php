<div class="table-responsive">
    <table class="table m-0">
    <thead>
        <tr>
            <th>Contract Code</th>
            <th>Work Order ID</th>
            <th>Property</th>
            <th>Service Provider</th>
            <th>Labour</th>
            <th>Task Title</th>
            <th>Task Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse($tasks as $task)
        <tr>
           <td>{{$task->task->contract->code}}</td> 
           <td>{{$task->task->work_order_id}}</td> 
           <td>{{$task->task->property->property_name}}</td>
           <td>{{$task->task->contract->service_provider->name}}</td>
           <td>{{$task->userDetails->name}}</td>
           <td>{{$task->task->task_title}}</td>
           <td>{{Carbon::parse($task->task_date)->format('d/m/Y')}}</td>
 
        </tr>
        @empty
        <tr>
           <td colspan="7">No upcomming tasks</td> 
        </tr>
        @endforelse
    </tbody>
</table>
</div>