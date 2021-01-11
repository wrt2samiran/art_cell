<div class="table-responsive">
    <table class="table m-0">
      <thead>
          <tr>
              <th>Contract Code</th>
              <th>Work Order ID</th>
              <th>Complaint</th>
              <th>Status</th>
              <th>Created At</th>
          </tr>
      </thead>
      <tbody>
          @forelse($complaints as $complaint)
          <tr>
             <td>{{$complaint->contract->code}}</td> 
             <td>{{$complaint->work_order_id?$complaint->work_order_id:'N/A'}}</td> 
             <td>{{$complaint->details}}</td>
             <td>{{$complaint->complaint_status->status_name}}</td>
             <td>{{$complaint->created_at->format('d/m/Y')}}</td>
          </tr>
          @empty
          <tr>
             <td colspan="5">No complaints</td> 
          </tr>
          @endforelse
      </tbody>
  </table>
</div>