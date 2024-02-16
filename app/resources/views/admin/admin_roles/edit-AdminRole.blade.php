@extends('admin.master')
@section('title', 'Edit Permissions')
@section('breadcrumb') Edit Admin Role @endsection
@section('content')




<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Admin Role Edit </h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 col-12">
              <div class="card ">
<!--                <div class="card-header">Profile Information</div>
-->                <div class="card-body">
                  
                    <form method="POST" action="{{ route('update.admin.role') }}" aria-label="{{ __('Register') }}" >
                    
                        @csrf
                     <input type="hidden" name="id" value="{{ $adminRole->id }}">
                        <div class="form-group">
                            <label for="name" >{{ __('Role name :') }}</label>

                            <div >
                                <input id="role_name" type="text" class="form-control{{ $errors->has('role_name') ? ' is-invalid' : '' }}" name="role_name" value="{{ old('role_name',$adminRole->role_name) }}" required >

                                @if ($errors->has('role_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('role_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <?php $allCheckboxPermission = explode(",",$adminRole->permission);?>
                        @foreach($permissions as $permission)
    <label class="control control--checkbox">{{$permission->name}}
        <input  @if( in_array($permission->id,$allCheckboxPermission)) checked @endif type="checkbox" id="permission_id" name="permission[]" value="{{$permission->id}}"> 
        @if ($errors->has('permission'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('permission') }}</strong>
                                    </span>
                       @endif
        <div class="control__indicator"></div>
    </label>
    @endforeach

    <div id="divcheck" style="display:block; clear:both;over-flow:hidden;"> <span style="color:red">
              <strong>*** Please Select at least one Role</strong>
          </span>
</div>
                       

                        <div class="form-group mb-0">
                            <div class="" style="display:block; clear:both;over-flow:hidden;">
                                <button type="submit" class="bl-btn">
                                    {{ __('Update') }}
                                </button>
								</div>
                        </div>
                    </form>
                  
                  
                  
                  
                  
                  
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>



  <script>
  $(document).ready(function(){
    $("#divcheck").hide();
    $("form").submit(function(){
		if ($('input:checkbox').filter(':checked').length < 1)
		{  
      $("#divcheck").show();
      return false;
    }
    else
    $("#divcheck").hide();
		
    });
});
</script>

@endsection
