@extends('admin.master')
@section('title', 'Slider Settings')
@section('breadcrumb')Sliders @endsection
@section('content')

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>{{ _('messages.Whoops') }}!</strong> {{ _('messages.There were some problems with your input') }}.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            <ul>
                <li>{{ session('success') }}</li>
            </ul>
        </div>
    @endif
 
    <div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
      @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
        <h2>Sliders</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header">
                	<div class="search-area wth-btn">
                    
                     <div class="form-group">
					 
					  <a href="{{ route('admin.sliders') }}" style="text-decoration:none; color:#fff;padding: 10px 16px;position: relative;top: 7px;" class="bl-btn flt-left">Slider</a>
                        <a href="{{ route('admin.contentpages') }}"  style="text-decoration:none; color:#fff;padding: 10px 16px;position: relative;top: 7px;" class="bl-btn flt-left">Content Page</a>
					 
				
                    </div>
                    
                    </div>
                    
                    <a href="{{ route('slider.create') }}" class="bl-btn flt-right">Create Slider</a>
                    
                    
                </div>
                <div class="card-body">
                  <div class="tableC">
				  
                  @if($sliders->count() > 0) 
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                   <th>Created at</th>
                                    <th>Show Home</th> <th class="action-icon">Action</th>
                                </tr>
                      </thead>
                      <tbody>
                      @php $count = 0; @endphp
                      @forelse ($sliders as $row)
                      @php $count++; @endphp
                      
                      <tr>
                      
                         <td>{{ ($sliders->currentpage() - 1) * $sliders->perpage() + $count }}</td>
                                    <td> {{ $row->name }}</td>
                                    
                                    <td>{{ $row->created_at }}</td>
                                    <td>
                                       <div class="switch-field">
                                        <input type="radio" id="radio-{{$row->id}}" name="switch-{{$row->id}}" value="Yes" 
                                        {{$row->show_home=='Yes'?'checked':''}} onchange="activateslider({{$row->id}})" />
                                        <label for="radio-{{$row->id}}">On</label>
                                        <input type="radio" id="radio-1{{$row->id}}" name="switch-{{$row->id}}"  value="No" {{$row->show_home=='No'?'checked':''}} onchange="activateslider({{$row->id}})"/>
                                        <label for="radio-1{{$row->id}}">Off</label>

                                      </div>
                                    </td>
                          <td>
                            <div class="icon-bx"> <a href="{{ route('slider.edit', $row->id) }}"><i class="icon  cil-pencil"></i></a> 
						  <a href="{{ route('slider.show', $row->id) }}"><i class="fa fa-eye"></i></a> 
                          <a href="JavaScript:void(0);" onclick="deleteconfirm({{$row->id}})" ><i class="icon cil-trash"></i></a> </div></td>
                        </tr>
                        
                            
                      @endforeach 
                      
                      
                       
              
                      </tbody>
                    </table>
                    @else
                      <div class="alert alert-danger">No Results</div>
                        
                       @endif 
                  </div>
                  
                  {{ $sliders->links()}}
                 
                 
                </div>
                
                
                
                
                
                
                
                
                
           <div class="card-header">
                	<div class="search-area wth-btn">
                    
                     <div class="form-group">
					 
					  <h2>Mobile Sliders</h2>
                    </div>
                    
                    </div>
                    
                    <a href="{{ route('mobile_slider.create') }}" class="bl-btn flt-right">Create Mobile Slider</a>
                    
                    
                </div>     
          <div class="card-body">
                  <div class="tableC">
				  
                  @if($mobile_sliders->count() > 0) 
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                   <th>Created at</th>
                                    <th>Show Home</th> <th class="action-icon">Action</th>
                                </tr>
                      </thead>
                      <tbody>
                      @php $count = 0; @endphp
                      @forelse ($mobile_sliders as $row)
                      @php $count++; @endphp
                      
                      <tr>
                      
                         <td>{{ ($mobile_sliders->currentpage() - 1) * $mobile_sliders->perpage() + $count }}</td>
                                    <td> {{ $row->name }}</td>
                                    
                                    <td>{{ $row->created_at }}</td>
                                    <td>
                                       <div class="switch-field">
                                        <input type="radio" id="radio-{{$row->id}}" name="switch-{{$row->id}}" value="Yes" 
                                        {{$row->show_home=='Yes'?'checked':''}} onchange="mobile_activateslider({{$row->id}})" />
                                        <label for="radio-{{$row->id}}">On</label>
                                        <input type="radio" id="radio-1{{$row->id}}" name="switch-{{$row->id}}"  value="No" {{$row->show_home=='No'?'checked':''}} onchange="mobile_activateslider({{$row->id}})"/>
                                        <label for="radio-1{{$row->id}}">Off</label>

                                      </div>
                                    </td>
                          <td>
                            <div class="icon-bx"> <a href="{{ route('mobile_slider.edit', $row->id) }}"><i class="icon  cil-pencil"></i></a> 
						  <a href="{{ route('mobile_slider.show', $row->id) }}"><i class="fa fa-eye"></i></a> 
                          <a href="JavaScript:void(0);" onclick="deleteconfirm_mobile({{$row->id}})" ><i class="icon cil-trash"></i></a> </div></td>
                        </tr>
                        
                            
                      @endforeach 
                      
                      
                       
              
                      </tbody>
                    </table>
                    @else
                      <div class="alert alert-danger">Mobile sliders not set yet</div>
                        
                       @endif 
                  </div>
                  
                  {{ $mobile_sliders->links()}}
                 
                 
                </div>
                
                
                
                
                
                
                
                
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <script src="{{asset('/admin1/js/sweetalert.js')}}"></script>
<script>
  function activateslider(id){
    var status=$("input[name='switch-"+id+"']:checked").val();
    swal({
    title:"Do you want to continue ? " ,
    text: "",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      
      $.ajax({
         url: "{{url('updateactiveslider')}}",
            type: "post",
            data:{ 
                _token:'{{ csrf_token() }}',
                  id: id,
                  status: status,
            },
            async:true,
            cache: false,
            dataType: 'json',
            success: function(data){
              if(data)
                  swal("Active Slider Changed", "", "success");
              else
                  swal("Error On Submission", "", "error");
              location.reload(true);
          } ,
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
            swal(errorThrown, "", "error");
          }  

        })
    } 
    else {
        
           var cur_status=$("input[name='switch-"+id+"']:checked").val();
           if(cur_status=='Yes')
              {
                $("#radio-"+id).prop('checked',false);
                $("#radio-1"+id).prop('checked',true);
              }
           else
              {
                $("#radio-"+id).prop('checked',true);
                $("#radio-1"+id).prop('checked',false);
              }
          
        }
    });  

  }
  
  
  
  function mobile_activateslider(id){
    var status=$("input[name='switch-"+id+"']:checked").val();
    swal({
    title:"Do you want to continue ? " ,
    text: "",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      
      $.ajax({
         url: "{{url('mobile_updateactiveslider')}}",
            type: "post",
            data:{ 
                _token:'{{ csrf_token() }}',
                  id: id,
                  status: status,
            },
            async:true,
            cache: false,
            dataType: 'json',
            success: function(data){
              if(data)
                  swal("Active Slider Changed", "", "success");
              else
                  swal("Error On Submission", "", "error");
              location.reload(true);
          } ,
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
            swal(errorThrown, "", "error");
          }  

        })
    } 
    else {
        
           var cur_status=$("input[name='switch-"+id+"']:checked").val();
           if(cur_status=='Yes')
              {
                $("#radio-"+id).prop('checked',false);
                $("#radio-1"+id).prop('checked',true);
              }
           else
              {
                $("#radio-"+id).prop('checked',true);
                $("#radio-1"+id).prop('checked',false);
              }
          
        }
    });  

  }
  
  
  
  
  
  function deleteconfirm(data){
  var id=data;
  var deleteurl="{{route('slider.destroy', ':id')}}".replace(':id', '')+data;

  swal({
    title:"Do you really want to delete? " ,
    text: "",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      window.location=deleteurl;
    } 
    else {
          
        }
    });  

}




  function deleteconfirm_mobile(data){
  var id=data;
  var deleteurl="{{route('mobile_slider.destroy', ':id')}}".replace(':id', '')+data;

  swal({
    title:"Do you really want to delete? " ,
    text: "",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      window.location=deleteurl;
    } 
    else {
          
        }
    });  

}









</script>
 
<style type="text/css">
  

.switch-field {
  display: flex;
  overflow: hidden;
}

.switch-field input {
  position: absolute !important;
  clip: rect(0, 0, 0, 0);
  height: 1px;
  width: 1px;
  border: 0;
  overflow: hidden;
}

.switch-field label {
  background-color: #e4e4e4;
  color: rgba(0, 0, 0, 0.6);
  font-size: 14px;
  line-height: 1;
  text-align: center;
  padding: 8px 16px;
  margin-right: -1px;
  border: 1px solid rgba(0, 0, 0, 0.2);
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
  transition: all 0.1s ease-in-out;
}

.switch-field label:hover {
  cursor: pointer;
}

.switch-field input:checked + label {
  background-color: #0b6eb1; color:#fff;
  box-shadow: none;
}
.switch-field label{box-shadow:none;background-color: #fff;}
.switch-field label:first-of-type {
  border-radius: 4px 0 0 4px;
}

.switch-field label:last-of-type {
  border-radius: 0 4px 4px 0;
}

</style>


@endsection