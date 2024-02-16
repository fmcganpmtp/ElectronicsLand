@extends('admin.master')
@section('title', 'Edit Slider')
@section('breadcrumb') Edit Slider @endsection
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
        <h2>Edit Slider</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 col-12">
              <div class="card ">
              <div class="card-body">
                  
                    <form method="POST" action="{{ route('slider.update', $slider_data->id) }}"  enctype="multipart/form-data">
                    
                     @csrf
                     <input type="hidden" name="brand_id" value="{{ $slider_data->id }}">
                        <div class="form-group">
                            <label for="name" >Slider Title</label> 
							<input type="text" name="slider_title" value="{{$slider_data->name}}" class="form-control" required="">
                          </div>
                    
                        
						
					<div class="row" id="fields_extent" >	
						<div id="myElem"></div>
                    @foreach($slider_images as $images)
                        <div class="row clone-group" id="outer_media_{{$images->id}}">
                           
                                <div class="form-group">
                                    <label>Image</label>
                                    <div class="input-group control-group increment" >
                                        @if($images->image!='')<img src="{{ asset('/assets/uploads/sliders/'.$images->image) }}" class="img-thumbnail" width="175" />@endif
                                        <input type="file" name="image[]"  accept="image/png, image/gif, image/jpeg" class="form-control"  style="height: 40px !important; ">
                                        <div class="input-group-btn">
                                        <button class="btn btn-danger delete_ext" type="button" onclick="removeMedia({{$images->id}})"><i class="fa fa-times-circle" style="color:#fff;"></i></button>
                                        </div>
                                    </div>
                                </div>
                            
                            
                                <div class="form-group">
                                    <label>Title on Image</label> <input type="text" name="old_title_on_image[]" value="{{$images->title}}" class="form-control">
                                </div>
                            
                                <div class="form-group">
                                    <label>Description </label> <textarea name="old_description[]" class="form-control">{{$images->description}}</textarea>
                                </div>
                            
                                <div class="form-group">
                                    <label>Image Target </label> <input type="text" name="old_image_target[]" value="{{$images->target}}" class="form-control">
                                </div>
                            
                            <input type="hidden" name="old_image_id[]" value="{{$images->id}}" />
                        </div>
                    @endforeach
                    
                        <div class="form-group">
                            <label>Image</label>
                            <div class="input-group control-group increment" >
                                <input type="file" name="image[]" class="form-control"  style="height: 40px !important; ">
                                <div class="input-group-btn">
                                    <button class="btn btn-success" type="button"><i class="fa fa-plus" style="color:#fff;"></i></button>
                                </div>
                            </div>
                        </div>
                    

                        <div class="form-group">
                            <label>Title on Image</label> <input type="text" name="title_on_image[]" value="" class="form-control">
                        </div>
                   
                        <div class="form-group">
                            <label>Description </label> <textarea name="description[]" class="form-control"></textarea>
                        </div>
                   
                        <div class="form-group">
                            <label>Image Target </label> <input type="text" name="image_target[]" value="" class="form-control">
                        </div>
                    

                </div>
						
						
				</div>		
						
					

						                <div class="row clone-group" id="clone" style="display: none;">
                    <div class="row clone-group" >
                        
                            <div class="form-group">
                                <label>Image</label>
                                <div class="input-group control-group increment" >
                                    <input type="file" name="image[]" class="form-control">
                                    <div class="input-group-btn">
                                        <button class="btn btn-danger delete_new" type="button"><i class="fa fa-times-circle" style="color:#fff;"></i></button>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="form-group">
                                <label>Title on Image</label> <input type="text" name="title_on_image[]" value="" class="form-control">
                            </div>
                        
                            <div class="form-group">
                                <label>Description </label> <textarea name="description[]" class="form-control"></textarea>
                            </div>
                        
                            <div class="form-group">
                                <label>Image Target </label> <input type="text" name="image_target[]" value="" class="form-control">
                            </div>
                        
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

  <script src="{{ asset('admin1/js/sb-admin-2.min.js') }}"></script>

  <script type="text/javascript">
    $(document).ready(function() {
        $(".btn-success").click(function(){ 
            var html = $("#clone").html();
            $("#fields_extent").append(html);
        });
        $("body").on("click",".delete_new",function(){
            $(this).parents(".clone-group").remove();
        });

    });
    function removeMedia(cid){ 

       if(cid){
           var outerhtml =  $("#outer_media_"+cid).html();
           $("#outer_media_"+cid).html('<img style="width:50px;" src="{{ asset('uploads/defaultImages/loader.gif') }}" >')
           $.ajax({

                type:"post",
                data: { id: cid, "_token": "{{ csrf_token() }}" },
                url:"{{ route('slider.removeMedia') }}", //Please see the note at the end of the post**
                success:function(res)
                {
                    if(res.ajax_status=='success'){
                        html = '';
                        $("#outer_media_"+cid).html(html);
                        $("#outer_media_"+cid).remove();
                        $("#myElem").html(res.message);
                        $("#myElem").show().delay(3000).fadeOut();

                    }else{
                        $("#outer_media_"+cid).html(outerhtml);
                        $("#myElem").html(res.message);
                        $("#myElem").show().delay(3000).fadeOut();
                    }
                }

            });
       }
   }
</script>

@endsection
