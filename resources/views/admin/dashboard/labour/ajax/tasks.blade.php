<div class="table-responsive">
    <table class="table m-0">
        <thead>
            <tr>
                <th>Task Title</th>
                <th>Task Date And Time</th>
                <th>Property</th>
                <th>Service</th>
                <th>Task Finish Date and Time (Scheduled)</th>
                <th>Slot</th>
                <th>Created At</th>
            </tr>
            </tr>
        </thead>
        <tbody>
            @forelse($task_details_upcoming_list as $task_upcoming)
            <tr>
               <td>{{@$task_upcoming->task->task_title}}</td> 
               <td>{{@$task_upcoming->task_date}}</td> 
               <td>{{@$task_upcoming->task->property->property_name}}</td>
               <td>{{@$task_upcoming->service->service_name}}</td>
               <td>{{@$task_upcoming->created_at->format('d/m/Y')}}</td>
               <td>{{@$task_upcoming->get_slot_name()}}</td>
               <td>{{@$task_upcoming->created_at->format('d/m/Y')}}</td>                             
            </tr>
            @empty
            <tr>
               <td colspan="7">No upcomming tasks</td> 
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>