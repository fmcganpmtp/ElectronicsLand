@extends('admin.master')
@section('title', 'Front End Menu Manager')
@section('breadcrumb') Front End Menu Manager @endsection
@section('content')




 
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
      @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
    
        <h2>Front End Menu Manager</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header"> </div>
                <div class="card-body">
                  
                  
 <form method="post" action ="{{route('admin.savefrontendmenu')}}">
 @csrf

    <div class="row chk-bxs" style="margin-bottom:0px;">
    <div class="col-lg-12 col-12">
    @foreach($Category as $data)
    <label class="control control--checkbox">{{$data->name}}
        <input  @if( in_array($data->id,$FrontEndMenu)) checked @endif type="checkbox" id="Category" name="category_id[]" value="{{$data->id}}"> 
       <div class="control__indicator"></div>
    </label>
    @endforeach

</div>
</div>
<div id="divcheck"> <span style="color:red">
              <strong>*** Please Select at least One Ctegory</strong>
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