@extends('admin.master')
@section('title', 'Edit Testimonial')
@section('breadcrumb') Edit Testimonial @endsection
@section('content')

@section('content')



<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Edit Testimonial</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 col-12">
              <div class="card ">
              <div class="card-body">
                  
                    <form method="POST" action="{{ route('testimonials.edit', $testimonials->id) }}"  enctype="multipart/form-data">
                    
                     @csrf
                     <input type="hidden" name="testimonial_id" value="{{ $testimonials->id }}">
                        <div class="form-group">
                            <label for="name" >{{ __('Name') }}  <span class="color_red">*</span></label>

                            <div >
                            <input id="name" type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name',$testimonials->name) }}" required >
                                 @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    
                        
						
						<div class="form-group">
                            <label for="name" >{{ __('Company') }}  <span class="color_red">*</span></label>
                            <div >
                             <input id="company" type="text" required placeholder="Company" class="form-control {{ $errors->has('company') ? ' is-invalid' : '' }}" name="company" value="{{ old('company',$testimonials->company_name) }}">
                             @if ($errors->has('company'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('company') }}</strong>
                                 </span>
                             @endif
                            </div>
                        </div>
						
						
						<div class="form-group">
                            <label for="name" >{{ __('Title') }}  <span class="color_red">*</span></label>

                            <div >
                             <input id="title" type="text" required placeholder="Title" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title',$testimonials->title) }}">   
                             @if ($errors->has('title'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('title') }}</strong>
                                 </span>
                             @endif
                            </div>
                        </div>
						
						<div class="form-group">
                        <div id="image-block">
                                <div class="form-group">
                                    @if ($testimonials->profile_pic != '')
                                    <img src="{{ asset('/assets/uploads/testimonials/') }}/{{ $testimonials->profile_pic }}" alt="{{ $testimonials->profile_pic }}" width="200px" />
                                    
                                    
                                    <a href="javascript:void(0)" onclick="removeImage({{ $testimonials->id }})" class=""><span class="red_round remove-input-field"><i class="fa fa-minus-circle" aria-hidden="true"></i></span></a>
                                @endif
                                </div>
                            </div>
                            <label for="file" >{{ __('Profile Picture') }}</label>

                            <div >
							 <input id="profile_pic" type="File" class="course-img" name="profile_pic">
                            </div>
                            </div>
                        
						
						<div class="form-group">
                            <label for="name" >{{ __('Comments') }}  <span class="color_red">*</span></label>

                            <div >
                             <textarea placeholder="Comments" class="form-control {{ $errors->has('comments') ? ' is-invalid' : '' }}" name="comments" required>{{ old('comments',$testimonials->comments) }}</textarea>  
                               @if ($errors->has('comments'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('comments') }}</strong>
                                 </span>
                             @endif 
                            </div>
                        </div>
						

        <div class="form-group">
         <label for="name" >{{ __('Star Rating') }}  </label> 
         <div >
  <div class="rating-css">
    <div class="star-icon start1">
        <input type="radio" value="1" name="star_rating" id="rating1" @if($testimonials->star_rating == 1) checked="checked" @endif>
        <label for="rating1"  onclick="removefade(1)"  @if($testimonials->star_rating >= 1) class="fa fa-star "   @else class="fa fa-star colorfade"    @endif id="rating1" ></label>
        <input type="radio"  value="2" name="star_rating" id="rating2" @if($testimonials->star_rating == 2) checked="checked" @endif>
        <label for="rating2" onclick="removefade(2)"  @if($testimonials->star_rating >= 2) class="fa fa-star "  @else class="fa fa-star colorfade "   @endif id="rating2"></label>
        <input type="radio" value="3" name="star_rating" id="rating3" @if($testimonials->star_rating == 3) checked="checked" @endif>
        <label for="rating3" onclick="removefade(3)"   @if($testimonials->star_rating >= 3) class="fa fa-star "  @else class="fa fa-star colorfade "   @endif id="rating3"></label>
        <input type="radio" value="4" name="star_rating" id="rating4" @if($testimonials->star_rating == 4) checked="checked" @endif>
        <label for="rating4" onclick="removefade(4)"   @if($testimonials->star_rating >= 4) class="fa fa-star "  @else  class="fa fa-star colorfade "  @endif  id="rating4"></label>
        <input type="radio" value="5" name="star_rating"  id="rating5" @if($testimonials->star_rating == 5) checked="checked" @endif>
        <label for="rating5" onclick="removefade(5)"   @if($testimonials->star_rating >= 5) class="fa fa-star "  @else  class="fa fa-star colorfade" @endif id="rating5"></label>
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
                        type:'POST',
                        url:'{{ route("testimonials.removeImage") }}',
                        data:{id: id, '_token':'{{csrf_token()}}'},
                        success:function(response){
                            if(response.result){
                                $('#image-block').replaceWith('<span class="text-success" id="alert_image">Testimonial image removed successfully.</span>');
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

<script>
function removefade(cnt) {
 $(document).find(".colorfade").removeClass("colorfade");
}
</script>