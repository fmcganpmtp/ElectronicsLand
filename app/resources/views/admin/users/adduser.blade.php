@extends('admin.master')
@section('title', 'Create User')
@section('breadcrumb') Create User @endsection
@section('content')




<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Admin User Create</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 col-12">
              <div class="card ">
<!--                <div class="card-header">Profile Information</div>
-->                <div class="card-body">
                    <form method="POST" action="{{ route('save.user') }}" aria-label="{{ __('Register') }}"  enctype="multipart/form-data">
                     
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
                            <label for="email" >{{ __('E-Mail Address') }} <span class="color_red">*</span></label>

                            <div >
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label for="username" >{{ __('Username') }} <span class="color_red">*</span></label>

                            <div >
                                <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required >

                                @if ($errors->has('username'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> -->
                        
                        <div class="form-group">
                            <label for="phone" >{{ __('Phone') }} <span class="color_red">*</span></label>
 
                            <div >
                                <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone') }}" maxlength="15" required >

                                @if ($errors->has('phone'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="file" >{{ __('Profile Pic') }}</label>

                            <div >
							<input type="file" name="image"  class="form-control" accept="image/png, image/gif, image/jpeg">
                            </div>
                        </div>
						<div class="form-group">
                            <label for="about" >{{ __('About') }}</label>

                            <div >
                                <textarea name="about" id="about" class="form-control{{ $errors->has('about') ? ' is-invalid' : '' }}">{{ old('about') }}</textarea>
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
                                        <option value="{{$adminrole->id}}">{{$adminrole->role_name}}</option>
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
                            <label for="job_title" >Job Title <span class="color_red">*</span></label>
 
                            <div >
                              <input type="text" name="job_title" value="{{ old('job_title') }}"  class="form-control" required>
                             @if ($errors->has('job_title'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('job_title') }}</strong>
                                    </span>
                            @endif
</div>
</div>




                        <div class="form-group">
                            <label for="password" >{{ __('Password') }} <span class="color_red">*</span></label>

                            <div >
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" >{{ __('Confirm Password') }} <span class="color_red">*</span></label>

                            <div >
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <div class="">
                                <button type="submit" class="bl-btn">
                                    {{ __('Register') }}
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