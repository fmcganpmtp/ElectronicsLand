@extends('admin.master')
@section('title', 'Social Media')
@section('breadcrumb') Social Media @endsection
@section('content')

@if (count($errors) > 0)
    <script type="text/javascript">
        $( document ).ready(function() {
             $('#socialcreateModal').modal('show');
        });
    </script>
  @endif


 
    <div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
      @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
        <h2>Social Media</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header">
                	<div class="search-area wth-btn">
                    
                     <div class="form-group">
					 
					 <form method="get">
                       <input type='text' class="form-control" placeholder="Search Keyword" name="search_keyword" value="{{ request()->get('search_keyword') }}">
                       <button type="submit"><i class="icon cil-search"></i></button>
                      </form>
					 
				
                    </div>
                    
                    </div>
                    
                    <a href="javascript:void(0)" class="bl-btn flt-right"  data-toggle="modal" data-target="#socialcreateModal">Create New</a>
                    
                    
                </div>
                <div class="card-body">
                  <div class="tableC">
				  
                    <div class="modal fade" id="socialcreateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Create Social Media</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="post" action="{{ route('socialmedia.create') }}" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        @csrf
                                        <div class="col-md-12">
                                            <label>Name</label>
                                            <input type="text" name="name" class="form-control" value="{{old('name')}}">
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Link</label>
                                            <input type="text" name="link" class="form-control" value="{{old('link')}}">
                                            <span class="text-danger">{{ $errors->first('link') }}</span>
                                        </div>
                                        <br>
                                        <div class="col-md-12">
                                            <label>Icon</label><br>
                                            Icon <input type="radio" name="file_type" value="icon" {{ (old('file_type') != '' && old('file_type') == 'icon') ? 'checked' : 'checked' }}>
                                            &nbsp;File <input type="radio" name="file_type" value="image" {{ (old('file_type') != '' && old('file_type') == 'image') ? 'checked' : '' }}>
                                            <input type="text" name="icon" class="form-control texttype"  {{ (old('file_type') != '' && old('file_type') == 'icon') ? '' : (empty(old('file_type')) ? '' : 'style=display:none') }}>
                                            <input type="file"  accept="image/png, image/gif, image/jpeg" name="icon" class="form-control filetype" {{ (old('file_type') != '' && old('file_type') == 'image') ? '' : (empty(old('file_type')) ? 'style=display:none' : 'style=display:none') }}>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="bl-btn">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                  @if($social_media->count() > 0) 
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                            <th>Name</th>
                            <th>Link</th>
                            <th>Icon</th>
                            <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      @php $count = 0; @endphp
                      @foreach ($social_media as $key => $media_data)
                      
                     <tr>
                                
                                <td> {{$media_data->name}}</td>
                                <td><a href="{{ $media_data->link }}" target="_blank">{{ $media_data->link }}</a></td>
                                <td>
                                    @if ($media_data->type == 'image')<img src="{{ asset('assets/uploads/socialmedia/' . $media_data->icon) }}" width="50px">@else {!! $media_data->icon !!} @endif
                                </td>
                          <td>
						  
						  
						  <div class="icon-bx"> 
						  
							 <a href="javascript:void(0)"  data-toggle="modal" data-target="#exampleModal{{ $media_data->id }}"><i class="icon  cil-pencil"></i></a>
                    			
						  <!--<a href="{{ route('testimonials.edit',$media_data->id) }}"><i class="icon  cil-pencil"></i></a> -->
                          <a  href="JavaScript:void(0);" onclick="deleteconfirm({{$media_data->id}})"><i class="icon cil-trash"></i></a> </div></td>
                        
						
						<!-- Modal -->
                                <div class="modal fade" id="exampleModal{{ $media_data->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Social Media</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="post" action="{{ route('socialmedia.update') }}" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    @csrf
                                                    <input type="hidden" name="table_id" value="{{ $media_data->id }}">
                                                    <div class="col-md-12">
                                                        <label>Name</label>
                                                        <input type="text" name="name" value="{{ $media_data->name }}" class="form-control">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label>Link</label>
                                                        <input type="text" name="link" value="{{ $media_data->link }}" class="form-control">
                                                    </div>
                                                    <br>
                                                    <div class="col-md-12">
                                                        Current Icon:
                                                        @if ($media_data->type == 'image')
                                                            <img src="{{ asset('assets/uploads/socialmedia/') }}/{{ $media_data->icon }}" alt="{{ $media_data->icon }}" width="200px" /><br>
                                                        @else
                                                            {!! $media_data->icon !!}
                                                        @endif
                                                        <br><label>Icon : {{$media_data->type}}</label><br>
                                                        Icon <input type="radio" name="file_type" value="icon" {{ $media_data->type == 'icon' ? 'checked' : '' }}>
                                                        &nbsp;File <input type="radio" name="file_type" value="image" {{ $media_data->type == 'image' ? 'checked' : '' }}>
                                                        <input type="text" name="icon" value="{{ $media_data->type == 'icon' ? $media_data->icon : '' }}" class="form-control texttype" placeholder="" style="{{ $media_data->type == 'icon' ? '' : 'display:none' }}">
                                                        <input type="file" accept="image/png, image/gif, image/jpeg" name="icon" class="form-control filetype" style="{{ $media_data->type == 'image' ? '' : 'display:none' }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="bl-btn">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
						
						</tr>
                        
                            
                      @endforeach 
                      
                      
                       
              
                      </tbody>
                    </table>
                    @else
                      <div class="alert alert-danger">No Results</div>
                        
                       @endif 
                  </div>
                  
                  {{ $social_media->links()}}
                 
                 
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
  var deleteurl="{{ route('socialmedia.destroy', ':id') }}".replace(':id', '')+data;

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
        $(document).ready(function() {
            $("input[type='radio']").change(function() {
                var par_elm = $(this).closest('.modal-body');
                if ($(this).val() == "icon") {
                    par_elm.find('.filetype').hide();
                    par_elm.find('.texttype').show();
                } else {
                    par_elm.find('.filetype').show();
                    par_elm.find('.texttype').hide();
                }
            });
        });
    </script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
@endsection