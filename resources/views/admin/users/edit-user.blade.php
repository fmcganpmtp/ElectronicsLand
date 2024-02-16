@extends('admin.master')
@section('title', 'Edit Users')
@section('breadcrumb') Edit Users @endsection
@section('content')




<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Admin Edit User</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 col-12">
              <div class="card ">
<!--                <div class="card-header">Profile Information</div>
-->                <div class="card-body">
                  
                    <form method="POST" action="{{ route('update.user') }}" aria-label="{{ __('Register') }}" enctype="multipart/form-data">
                    
                        @csrf
                     <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <div class="form-group">
                            <label for="name" >{{ __('Name') }}  <span class="color_red">*</span></label>

                            <div >
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $user->name }}" required >

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    
                        <div class="form-group">
                            <label for="email" >{{ __('E-Mail Address') }}  <span class="color_red">*</span></label>

                            <div >
                                <input  id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{  $user->email }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                   
                        
                        <div class="form-group">
                            <label for="phone" >{{ __('Phone') }}  <span class="color_red">*</span></label>

                            <div >
                                <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" maxlength="15" value="{{  $user->phone }}" required >

                                @if ($errors->has('phone'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="about" >{{ __('About') }}</label>

                            <div >
                                <textarea name="about" id="about" class="form-control{{ $errors->has('about') ? ' is-invalid' : '' }}">{{  $user->about }}</textarea>
                                @if ($errors->has('about'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('about') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <label for="phone" >{{ __('Role') }} <span class="color_red">*</span></label>
 
                            <div >
                                <select name="adminrole" class="form-control{{ $errors->has('adminrole') ? ' is-invalid' : '' }}">
                                 <option value="">Select</option>  
                                @foreach($adminroles as $adminrole) 
                                        <option @if($adminrole->id== $user->adminrole) selected @endif value="{{$adminrole->id}}">{{$adminrole->role_name}}</option>
                                    @endforeach
                             </select>
                             @if ($errors->has('adminrole'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('adminrole') }}</strong>
                                    </span>
                            @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="job_title" >{{ __('Job Title') }}  <span class="color_red">*</span></label>

                            <div >
                                <input id="job_title" type="text" class="form-control{{ $errors->has('job_title') ? ' is-invalid' : '' }}" name="job_title" value="{{$user->job_title}}" required>

                                @if ($errors->has('job_title'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('job_title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group">
                        <?php $prof_pic_url =  URL::asset('/uploads/userImages/').'/'.$user->profile_pic; ?>
						@if(!empty($user->profile_pic))
                        <div id="profile">
                        <button type="button" onclick="removeImage({{$user->id}})" class="btn text-danger">X</button>
                        <img style=" width:100px !important;" class="pr_img" src="{{$prof_pic_url}}">
                        </div>
                        @endif
                            <label for="file" >{{ __('Profile Pic') }}</label>

                            <div >
							<input type="file" name="image"  class="form-control" accept="image/png, image/gif, image/jpeg" >
                            </div>
                        </div>
                        
                        
                        <div class="form-group">
                            <label for="password" >{{ __('Password') }}  <span class="color_red">*</span></label>

                            <div >
                                <input value="" id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" >{{ __('Confirm Password') }}  <span class="color_red">*</span></label>

                            <div >
                                <input  id="password-confirm" type="password" class="form-control" name="password_confirmation" >
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

<script>
        function removeImage(id){ 
            if(confirm('Do you want to remove image?')){
                $.ajax({
                        type:'POST',
                        url:'{{ url("deleteprofileimage")}}',
                        data:{id: id, '_token':'{{csrf_token()}}'},
                        success:function(response){
                            $("#profile").remove();
                        }
                    });
            }
        }
</script>    



@endsection
