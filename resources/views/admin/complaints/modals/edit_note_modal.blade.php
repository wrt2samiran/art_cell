<div class="modal" id="edit_note_modal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Note</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form  method="post" id="edit_note_form" action=""  enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!-- Modal body -->
            <div class="modal-body">
                <div class="form-group required">
                  <label for="note">Note <span class="error">*</span></label>
                  <textarea class="form-control" name="note" id="note_edit"  placeholder="Note">{!!old('note')?old('note'):''!!}</textarea>
                  @if($errors->has('note'))
                  <span class="text-danger">{{$errors->first('note')}}</span>
                  @endif
                </div>

                @php
                $user_type=auth()->guard('admin')->user()->role->user_type->slug;
                @endphp
                @if(in_array($user_type,['super-admin','service-provider']))
                <div class="form-group">
                  <label for="note">Note will be visible to </label><br>
                  <div class="form-check-inline">
                    <label class="form-check-label">
                      <input type="checkbox" name="visible_to[]" checked  disabled class="form-check-input" value="help desk">Help Desk
                    </label>
                  </div>
                  <div class="form-check-inline">
                    <label class="form-check-label">
                      <input name="visible_to[]" type="checkbox" class="form-check-input" checked {{($user_type=="service-provider")?"disabled":""}} value="service provider">Service Provider
                    </label>
                  </div>
                  <div class="form-check-inline">
                    <label class="form-check-label">
                      <input name="visible_to[]" id="property_owner_visibility" type="checkbox" class="form-check-input" value="property owner & manager">Property Owner & Manager
                    </label>
                  </div>
                  <div class="form-check-inline">
                    <label class="form-check-label">
                      <input name="visible_to[]" id="labour_visibility" type="checkbox" class="form-check-input" value="labour">Labour
                    </label>
                  </div>
                </div>
                @endif
                <div class="form-group required">
                  <label for="file">Attach File</label>
                  <input type="file" class="form-control note_file" id="file_edit" name="file">
                  <div id="file_help_text"></div>
                  @if($errors->has('file'))
                  <span class="text-danger">{{$errors->first('file')}}</span>
                  @endif
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
             <button class="btn btn-success">Update Note</button> <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
       </form>
    </div>
  </div>
</div>