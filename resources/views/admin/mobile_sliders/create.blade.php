@extends('admin.master')
@section('title', 'Create Slider')
@section('breadcrumb') Create Slider @endsection
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
    
    <div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Create Slider</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 col-12">
              <div class="card ">
              <div class="card-body">
<form method="POST" action="{{ route('mobile_slider.insert') }}"  enctype="multipart/form-data">
                   
                     @csrf
                     
                     
                 
                     <div class="form-group">
                                <label for="name" >Slider Title <span class="color_red">*</span></label> 
								<input type="text" name="slider_title" value="{{ old('slider_title') }}" class="form-control">
                            </div>
							
							
							
						<div class="form-group"  id="fields_extent">
                                <label for="name" >Image <span class="color_red">*</span></label> 
								<div class="input-group control-group increment">
                                    <input type="file" name="image[]" class="form-control" accept="image/png, image/gif, image/jpeg" style="height: 40px !important; ">
                                    <span class="btn btn-success round-button"><i class="fa fa-plus-circle" aria-hidden="true" style="color:#fff;"></i></span>

                                    
                                </div>
                           

						<div class="form-group">
                                <label for="name" >Title on Image </label> 
								<input type="text" name="title_on_image[]" value="" class="form-control" style=" height: 40px !important; ">
                            </div>

						<div class="form-group">
                                <label for="name" >Description </label> 
								<textarea name="description[]" class="form-control"></textarea>
                            </div>

                          <div class="form-group">
                                <label for="name" >Image Target </label> 
								<input type="text" name="image_target[]" value="" class="form-control">
                            </div>							
						 </div>	
							
							<div class="row clone-group" id="clone" style="display: none;">
                        <div class="row clone-group">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="name" >Image</label>
                                    <div class="input-group control-group increment">
                                        <input type="file" name="image[]" class="form-control" style=" height: 40px; ">
                                        <span class="red_round remove-input-field"><i class="fa fa-minus-circle" aria-hidden="true"></i></span>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="name" >Title on Image</label> <input type="text" name="title_on_image[]" value="" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="name" >Description </label> <textarea name="description[]" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="name" >Image Target </label> <input type="text" name="image_target[]" value="" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
							
                    
                     
                     
                     
                    <div class="form-group mb-0">
                         <div class="">
                             <button type="submit" class="bl-btn">
                                 {{ __('Save') }}
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
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script type="text/javascript">
        $(document).ready(function() { 
            $(".btn-success").click(function() {
                var html = $("#clone").html();
                $("#fields_extent").append(html);
            });

            $("body").on("click", ".remove-input-field", function() {
                $(this).parents(".clone-group").remove();
            });
        });
    </script>