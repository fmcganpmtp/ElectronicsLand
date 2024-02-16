@extends('admin.master')
@section('title', 'Create Admin Role')
@section('breadcrumb') Admin Role Creation @endsection
@section('content')




 
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Admin Role Creation</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header"> </div>
                <div class="card-body">
                  
                  
                  <form method="post" action ="{{route('save.admin.role')}}">
                    @csrf
                  <div class="row">
                  	<div class="col-lg-6 col-12">
                    	 <div class="form-group">
                      <label>Role name :</label>
                      <input type="text" name="role_name" value="{{old('role_name')}}"   class="form-control {{ $errors->has('role_name') ? ' is-invalid' : '' }}" >
                      @if ($errors->has('role_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('role_name') }}</strong>
                                    </span>
                       @endif
                    </div>
                    </div>
                  </div>
                  
                  
                  
  

    <div class="row chk-bxs" style="margin-bottom:0px;">
    <div class="col-lg-12 col-12">
    @foreach($permissions as $permission)
    <label class="control {{ $errors->has('permission') ? ' is-invalid' : '' }} control--checkbox">{{$permission->name}}
        <input type="checkbox" id="permission_id" name="permission[]" value="{{$permission->id}}"> 
        @if ($errors->has('permission'))
          <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('permission') }}</strong>
          </span>
                       @endif
        <div class="control__indicator"></div>
    </label>
    @endforeach

   
            

</div></div>
<div id="divcheck"> <span style="color:red">
              <strong>*** Please Select at least one Role</strong>
          </span>
</div>
                         <button type="submit" class="bl-btn">Submit</button>

</form>

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