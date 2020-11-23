<div class="modal" id="add_note_modal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Note</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form  method="post" id="add_note_form" action="{{route('admin.complaints.add_note',$complaint->id)}}"  enctype="multipart/form-data">
            @csrf
            <!-- Modal body -->
            <div class="modal-body">
                <div class="form-group required">
                  <label for="note">Note <span class="error">*</span></label>
                  <textarea class="form-control" name="note" id="note"  placeholder="Note">{!!old('note')?old('note'):''!!}</textarea>
                  @if($errors->has('note'))
                  <span class="text-danger">{{$errors->first('note')}}</span>
                  @endif
                </div>
                <div class="form-group required">
                  <label for="file">Attach File</label>
                  <input type="file" class="form-control note_file" id="file" name="file">
                  @if($errors->has('file'))
                  <span class="text-danger">{{$errors->first('file')}}</span>
                  @endif
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
             <button class="btn btn-success">Add Note</button> <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
       </form>
    </div>
  </div>
</div>