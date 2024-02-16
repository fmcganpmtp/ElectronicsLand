@extends('admin.master')
@section('title', 'Create Testimonial')
@section('breadcrumb') Create Testimonial @endsection
@section('content')

    <div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Create Testimonial</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 col-12">
              <div class="card ">
               <div class="card-body">
<form method="POST" action="{{ route('testimonials.create') }}"  enctype="multipart/form-data">
                    @csrf
                     
                     <div class="form-group">
                         <label for="name" >{{ __('Name') }} <span class="color_red">*</span></label>

                         <div >
                             <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required >

                             @if ($errors->has('name'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('name') }}</strong>
                                 </span>
                             @endif
                         </div>
                     </div>
					 
					 
					 
					 
					 <div class="form-group">
                         <label for="name" >{{ __('Company') }} </label><span class="color_red">*</span>

                         <div >
                             <input type="text" name="company" required value="{{ old('company') }}" class="form-control {{ $errors->has('company') ? ' is-invalid' : '' }}" >
                         
                             @if ($errors->has('company'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('company') }}</strong>
                                 </span>
                             @endif
                            </div>
                     </div>
					 
					 
					 
					 <div class="form-group">
                         <label for="name" >{{ __('Title') }} </label><span class="color_red">*</span>

                         <div >
                             <input type="text" name="title" required value="{{ old('title') }}" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}">
                         
                             @if ($errors->has('title'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('title') }}</strong>
                                 </span>
                             @endif
                            </div>
                     </div>
                     <div class="form-group">
                         <label for="file" >{{ __('Profile Pic') }}</label>

                         <div >
                         <input type="file" name="profile_pic" class="form-control">
                         
                         </div>
                     </div>
                     
                    <div class="form-group">
                         <label for="name" >{{ __('Comments') }} <span class="color_red">*</span> </label>
                          <div >
                             <textarea name="comments" class="form-control  {{ $errors->has('comments') ? ' is-invalid' : '' }}"  required>{{ old('comments') }}</textarea>
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
    <div class="star-icon">
        <input type="radio" value="1" name="star_rating" id="rating1">
        <label for="rating1" class="fa fa-star"></label>
        <input type="radio" value="2" name="star_rating" id="rating2">
        <label for="rating2" class="fa fa-star"></label>
        <input type="radio" value="3" name="star_rating" id="rating3">
        <label for="rating3" class="fa fa-star"></label>
        <input type="radio" value="4" name="star_rating" id="rating4">
        <label for="rating4" class="fa fa-star"></label>
        <input type="radio" value="5" name="star_rating" id="rating5">
        <label for="rating5" class="fa fa-star"></label>
    </div>
</div>
                        
                        </div>
                     </div>
					 
					
                     
                    <div class="form-group mb-0">
                         <div class="">
                             <button type="submit" class="bl-btn">
                                 {{ __('Submit') }}
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