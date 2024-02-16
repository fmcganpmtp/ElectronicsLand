@extends('admin.master')
@section('title', 'Edit Brand')
@section('breadcrumb') Edit Brand @endsection
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
   
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Edit Brand</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 col-12">
              <div class="card ">
              <div class="card-body">
                  
                    <form method="POST" action="{{ route('brands.update', $data->id) }}"  enctype="multipart/form-data">
                    
                     @csrf
                     <input type="hidden" name="brand_id" value="{{ $data->id }}">
                        <div class="form-group">
                            <label for="name" >{{ __('Name') }}  <span class="color_red">*</span></label>

                            <div >
                            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $data->name }}" required >
                                
                                
                                
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    
                        
                        <div class="form-group">
                        <div id="image-block">
                                <div class="form-group">
                                    @if ($data->image != '')
                                        <img src="{{ asset('/assets/uploads/brands/' . $data->image) }}" class="img-thumbnail" width="75" />
                                        <a href="javascript:void(0)" onclick="removeImage({{ $data->id }})" class="btn"><span class="red_round remove-input-field"><i class="fa fa-minus-circle" aria-hidden="true"></i></span></a>
                                    @endif
                                </div>
                            </div>
                            <label for="file" >{{ __('Logo') }}</label><span class="text-danger">(Max Image dimension width:150 x height:100 pixel, Max: 1MB)</span>

                            <div >
							<input type="file" name="image"  class="form-control" accept="image/png, image/gif, image/jpeg">
                            </div>
                        </div>
                        
                        
                        

                        <div class="form-group mb-0">
                            <div class="">
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





@endsection
<script type="text/javascript">
        function removeImage(id = null){ 
            if(confirm('Do you want to remove image?')){
                if(id != null){
                    $.ajax({
                        type:'GET',
                        url:'{{ route("brands.removeImage") }}',
                        data:{id: id, '_token':'{{csrf_token()}}'},
                        success:function(response){
                            if(response.result){
                                $('#image-block').replaceWith('<span class="text-success" id="alert_image">Brand image removed successfully.</span>');
                                $('#alert_image').delay(2000).fadeOut();
                                $('#image-block').remove();
                            } else {
                                alert(response.message);
                            }
                        }
                    });
                } else {
                    alert('Image remove failed. Something went wrong.');
                }
            }
        }
    </script>