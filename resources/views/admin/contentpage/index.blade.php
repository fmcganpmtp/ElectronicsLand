@extends('admin.master')
@section('title', 'Content Page')
@section('breadcrumb') Content Page @endsection
@section('content')

@if (count($errors) > 0)
        <div class="alert alert-danger">
        
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
        <h2>Content Pages</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header">
                	<div class="search-area wth-btn">
                    
                     <div class="form-group">
					 
					  <a href="{{ route('admin.sliders') }}" style="text-decoration:none;padding: 10px 16px;position: relative;top: 7px; color:#fff;" class="bl-btn flt-left">Slider</a>
                      
					 
				
                    </div>
                    
                    </div>
                    
                    <a href="{{route('contentpages.create') }}" class="bl-btn flt-right">Create Content</a>
                    
                    
                </div>
                <div class="card-body">
                  <div class="tableC">
				  
                  @if($contents->count() > 0) 
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                                    <th>#</th>
                                    <th>Page</th>
                                    <th>Title</th>
                                    <th>Position</th>
                                    <th>Created at</th>
                                    <th class="action-icon">Action</th>
                                </tr>
                      </thead>
                      <tbody>
                      @php $count = 0; @endphp
                      @forelse ($contents as $row)
                      @php $count++; @endphp
                      
                      <tr>
                                    <td>{{ ($contents->currentpage() - 1) * $contents->perpage() + $count }}</td>
                                    <td>{{ $row->page }}</td>
                                    <td>{{ $row->title }}</td>
                                    <td>{{ $row->page_position }}</td>
                                    <td>{{ $row->created_at }}</td>
                          <td>
						  
						  
						  <div class="icon-bx"> <a href="{{ route('contentpages.edit', $row->id) }}"><i class="icon  cil-pencil"></i></a> 
						  <a href="{{ route('contentpages.show', $row->id) }}"><i class="fa fa-eye"></i></a> 
                          <a href="JavaScript:void(0);" onclick="deleteconfirm({{ $row->id}})"><i class="icon cil-trash"></i></a> </div></td>
                        </tr>
                        
                            
                      @endforeach 
                      
                      
                       
              
                      </tbody>
                    </table>
                    @else
                      <div class="alert alert-danger">No Results</div>
                        
                       @endif 
                  </div>
                  
                  {{ $contents->links()}}
                
                 
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
  function deleteconfirm(data){
  var id=data;
  var deleteurl="{{ route('contentpages.destroy',':id') }}".replace(':id', '')+data;

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
 



@endsection