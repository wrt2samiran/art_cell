
@if($edit)
  <div class="row">
    @if(count($modules))
      @foreach($modules as $module)
      <div class="col-sm-4">
        <div class="card card-success">
          <div  class="card-header">{{$module->module_name}}</div>
          <div  class="card-body">
            @if(count($module->functionalities))
              @foreach($module->functionalities as $functionality)
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" id="customCheckbox_{{$functionality->id}}" name="functionalities[]" {{(in_array($functionality->id,$current_functionalities_id_array) && $editing_role->parrent_id==$parent_role->id)?'checked':''}} value="{{$functionality->id}}">
                <label for="customCheckbox_{{$functionality->id}}" class="custom-control-label">{{$functionality->function_name}}</label>
              </div>
              @endforeach
            @endif
          </div>
        </div>
      </div>
      @endforeach
    @else
    <div class="col-sm-12">
      <p>No Module Found</p>
    </div>
    @endif
  </div>
@else
  <div class="row">
    @if(count($modules))
      @foreach($modules as $module)
      <div class="col-sm-4">
        <div class="card card-success">
          <div  class="card-header">{{$module->module_name}}</div>
          <div  class="card-body">
            @if(count($module->functionalities))
              @foreach($module->functionalities as $functionality)
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" id="customCheckbox_{{$functionality->id}}" name="functionalities[]" value="{{$functionality->id}}">
                <label for="customCheckbox_{{$functionality->id}}" class="custom-control-label">{{$functionality->function_name}}</label>
              </div>
              @endforeach
            @endif
          </div>
        </div>
      </div>
      @endforeach
    @else
    <div class="col-sm-12">
      <p>No Module Found</p>
    </div>
    @endif
  </div>
@endif