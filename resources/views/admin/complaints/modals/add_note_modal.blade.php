<div class="modal" id="add_note_modal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">{{__('complaint_module.add_note')}}</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form  method="post" id="add_note_form" action="{{route('admin.complaints.add_note',$complaint->id)}}"  enctype="multipart/form-data">
            @csrf
            <!-- Modal body -->
            <div class="modal-body">
                <div class="form-group required">
                  <label for="note">{{__('complaint_module.labels.note')}} <span class="error">*</span></label>
                  <textarea class="form-control" name="note" id="note"  placeholder="{{__('complaint_module.placeholders.note')}}">{!!old('note')?old('note'):''!!}</textarea>
                  @if($errors->has('note'))
                  <span class="text-danger">{{$errors->first('note')}}</span>
                  @endif
                </div>
                @php
                $user_type=auth()->guard('admin')->user()->role->user_type->slug;
                @endphp
                @if(in_array($user_type,['super-admin','service-provider']))
                <div class="form-group">
                  <label for="note">{{__('complaint_module.labels.note_visibile')}} </label><br>
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
                      <input name="visible_to[]" checked type="checkbox" class="form-check-input" value="property owner & manager">Property Owner & Manager
                    </label>
                  </div>
                  <div class="form-check-inline">
                    <label class="form-check-label">
                      <input name="visible_to[]"  type="checkbox" class="form-check-input" value="labour">Labour
                    </label>
                  </div>
                </div>
                @endif
                <div class="form-group required">
                  <label for="file">{{__('complaint_module.labels.attach_file')}}</label>
                  <input type="file" class="form-control note_file" id="file" name="file">
                  @if($errors->has('file'))
                  <span class="text-danger">{{$errors->first('file')}}</span>
                  @endif
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
             <button class="btn btn-success">{{__('general_sentence.button_and_links.add_note')}}</button> <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('general_sentence.button_and_links.cancel')}}</button>
            </div>
       </form>
    </div>
  </div>
</div>