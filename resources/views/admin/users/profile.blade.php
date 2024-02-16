@extends('admin.master')
@section('title', 'User Profile')
@section('breadcrumb') Profile @endsection
@section('content')



    <div class="body flex-grow-1 px-3">
    <div class="container-lg">
     <div class="card-out mb-4 inner-form">
        <h2>Profile</h2>
        

        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 col-12">
              <div class="card ">
                <div class="card-header">Profile information</div>
                <div class="card-body">
                @if(Session::has('message')&&(Session::get('message')!="The old password does not match our records.")) <div class="alert alert-success">{{Session::get('message') }}</div> @endif

                  <form method="POST" action="{{ route('update.user') }}" aria-label="{{ __('Register') }}" enctype="multipart/form-data">
                    @csrf
                     <input type="hidden" name="user_id" value="{{ $user->id }}">
					<div class="form-group">
                      <label>Name:</label>
                      <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $user->name }}" required >

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                    </div>
                    
                    <div class="form-group">
                      <label>Email:</label>
                      <input  id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{  $user->email }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                    </div>
                    
                    
                    <div class="form-group">
                      <label>Phone:</label>
                      <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{  $user->phone }}" required >

                                @if ($errors->has('phone'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                    </div>
                    
                    
                    <div class="form-group">
                      <label>Job Title:</label>
                      <input id="job_title" type="text" class="form-control{{ $errors->has('job_title') ? ' is-invalid' : '' }}" name="job_title" value="{{  $user->job_title }}" required >

                                @if ($errors->has('job_title'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('job_title') }}</strong>
                                    </span>
                                @endif
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
    <label >Bio:</label>
    <textarea name="about" id="about" class="form-control{{ $errors->has('about') ? ' is-invalid' : '' }}">{{  $user->about }}</textarea>
                                @if ($errors->has('about'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('about') }}</strong>
                                    </span>
                                @endif
  </div>
  
  
  
<div class="file-drop-area form-group">
<label >Choose Profile Picture</label>
      <input type="file" name="image"  class="file-input form-control" accept=".jfif,.jpg,.jpeg,.png,.gif">
      @if($user->profile_pic)
      <div id="profile_pic">
           <img  style=" width:100px !important;" class="pr_img" src="{{URL::asset('/uploads/userImages/').'/'.$user->profile_pic}}">
           <button type="button" class="btn text-danger" onclick="removeImage()">X</button>
     </div>
      @endif

	</div>

	<div id="divImageMediaPreview">

	</div>
    
     <button type="submit" class="bl-btn">
                                    {{ __('Update') }}
                                </button>
                    
                  </form>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-12"> 
            
            
            <div class="card ">
                <div class="card-header">Change password</div>
                <div class="card-body">
                @if(Session::has('message')&&(Session::get('message')=="The old password does not match our records."))
<p class="alert alert-danger">{{ Session::get('message') }}</p>
@endif
                  <form action="{{ route('reset.admin.password.post') }}" method="POST">
				  <input type="hidden" name="email" value="{{ $user->email}}">
                          @csrf
                    <div class="form-group">
                      <label>Current password:</label>
                      <input name="oldPassword" type="password" class="form-control{{ $errors->has('oldPassword') ? ' is-invalid' : '' }}"  placeholder="" value="">
                     @if ($errors->has('oldPassword'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('oldPassword') }}</strong>
                            </span>
                        @endif
					</div>
                    
                    <div class="form-group">
                      <label>New password:</label>
                      <input value="" id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">

                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    
                    
                    <div class="form-group">
                      <label>Confirm new password:</label>
                      <input  id="password-confirm" type="password" class="form-control" name="password_confirmation" >
                    </div>
                    
              
                    
                    
                    
                     
  
  
  
  


    
    <button type="submit" class="bl-btn">update password</button>
                    
                  </form>
                </div>
              </div>
            
            
            
             </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<script src="{{asset('/admin1/js/sweetalert.js')}}"></script>
<script type="text/javascript">

  function removeImage(){ 
     swal({
        title: "Are you Sure you want to  Remove Profile Pic?",
        text: "",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
         url: "{{ url('removeprofilepic') }}",
            type: "get",
            async:true,
            cache: false,
            dataType: 'json',
            success: function(data){
              
             if(data)
              {
                $("#profile_pic").remove();
                swal('Successfully Removed', "", "success"); 
               
              }
             else 
               swal('Updation Failed', "", "error");
          } ,
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
            swal(errorThrown, "", "error");
          }  
        })  ;

        } else {
          
        }
      });  
  }
</script>






@endsection
