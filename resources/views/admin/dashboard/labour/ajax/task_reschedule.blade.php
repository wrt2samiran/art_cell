<div class="table-responsive">
    <table class="table m-0">
          <thead>
              <tr>
                  <!-- <th>ID</th> -->
                  <th>Task Title</th>
                  <th>Task Date And Time</th>
                  <th>Property</th>
                  <th>Service</th>
                  <th>Created At</th>
              </tr>
          </thead>
          <tbody>
              @forelse($task_details_reschedule_list as $reschedule_list)
              <tr>
                 <td>{{@$reschedule_list->task->task_title}}</td> 
                 <td>{{@$reschedule_list->task_date}}</td>
                 <td>{{@$reschedule_list->task->property->property_name}}</td>
                 <td>{{@$reschedule_list->service->service_name}}</td>
                 <td>{{@$reschedule_list->created_at->format('d/m/Y')}}</td>
              </tr>
              @empty
              <tr>
                 <td colspan="5">No Reschedule Request</td> 
              </tr>
              @endforelse
          </tbody>
      </table>
    </div>
</div>