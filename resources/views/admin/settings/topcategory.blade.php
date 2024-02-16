@extends('admin.master')
@section('title', 'Top Category')
@section('breadcrumb') Top Category @endsection
@section('content')




 
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
      @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
    
        <h2>Top Category Manager</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header"> </div>
                <div class="card-body">
                  
                  
 <form method="post" action ="{{route('admin.savetopcategory')}}">
 @csrf

    <div class="row chk-bxs" style="margin-bottom:0px;">
    <div class="col-lg-12 col-12">
    @foreach($Category as $data)
    <label class="control control--checkbox">{{$data->name}}
        <input  @if( in_array($data->id,$TopCategory)) checked @endif type="checkbox" id="Category" name="category_id[]" value="{{$data->id}}"> 
       <div class="control__indicator"></div>
    </label>
    @endforeach

</div>
</div>
<div id="divcheck"> </div>
<button type="submit" class="bl-btn">Submit</button>

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
    if($('input:checkbox').filter(':checked').length < 1)
    {  
      $("#divcheck").empty().append('<span style="color:red"><strong>*** Please Select at least One Ctegory</strong></span>');
      $("#divcheck").show();
     
      return false;
    }
    else if($('input:checkbox').filter(':checked').length >12)
    { 
       $("#divcheck").empty().append('<span style="color:red"><strong>*** Top Category Selection Restricted to 12 items</strong></span>'); 
      $("#divcheck").show();
      return false;
    }
    $("#divcheck").hide();
    
    });
});
</script>
@endsection