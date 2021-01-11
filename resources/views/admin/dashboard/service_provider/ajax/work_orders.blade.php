<div class="table-responsive">
    <table class="table m-0">
      <thead>
          <tr>
              <!-- <th>ID</th> -->
              <th>Contract Code</th>
              <th>Work Order Titile</th>
              <th>Type</th>
              <th>Service</th>
              <th>Created At</th>
          </tr>
      </thead>
      <tbody>
          @forelse($work_orders as $work_order)
          <tr>
             <!-- <td>{{$work_order->id}}</td> --> 
             <td>{{@$work_order->contract->code}}</td> 
             <td>{{@$work_order->task_title}}</td>
             <td>{{@$work_order->contract_services->service_type}}</td>
             <td>{{@$work_order->service->service_name}}</td>
             <td>{{@$work_order->created_at->format('d/m/Y')}}</td>
          </tr>
          @empty
          <tr>
             <td colspan="5">No complaints</td> 
          </tr>
          @endforelse
      </tbody>
  </table>
</div>