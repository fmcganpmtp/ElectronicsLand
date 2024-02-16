@extends('layouts.template')
@section('title', 'Edit Seller Profile')
@section('content')
<sectiion class="seller-page edt-seller-blade">
  <div class="pr-banner">
    <!--<img src="assets/images/pr-banner.jpg">-->
  </div>
  
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
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
	 
        <div class="pr-bottom">
		 <form method="post" action="{{route('update.seller.profile')}}" enctype='multipart/form-data' >
      @csrf
          <div class="card">
            <h3>Edit seller profile</h3>
            @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
            <div class="row">
              <div class="col-lg-3 col-12">
             <?php if(!empty(Auth::guard('user')->user()->profile_pic)) 
$img_path = asset('/uploads/userImages/').'/'.Auth::guard('user')->user()->profile_pic;
else  $img_path = asset('uploads/defaultImages/default_avatar.png'); ?>
                <div class="pr-logo">
				<img class="prof_img" src=" {{ $img_path }} "> <a href="javascript: void(0)" class="edit" id="upload_image"> <i class="fa fa-camera" aria-hidden="true"></i></a> </div>
                <input type="file" id="imgupload" class="image" name="image" style="display:none"/> 
				</div>
              <div class="col-lg-9 col-12">
                <div class="row">
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <label>Store Name</label>
                      <input class="form-control" type="text" name="store_name"  value="{{$user->store_name ?? ''}}">
                    </div>
                  </div>
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <label>Email</label>
                      <input type="email" class="form-control"  name="email" value="{{ $user->email ?? '' }}">
                    </div>
                  </div>
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <label>Phone</label>
                      <input type="text" class="form-control"  name="phone" value="{{ $user->phone ?? '' }}">
                    </div>
                  </div>
               
                  

                  <div class="row">
                  
                  <div class="col-lg-4 col-12">


<div class="form-check form-switch">
<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"  name="hide_promo_email" value="Yes" @if($user->hide_promo_email=="Yes") checked @endif >
<label class="form-check-label" for="flexSwitchCheckChecked">Promotional Emails</label>
</div></div>

<div class="col-lg-4 col-12">
<div class="form-check form-switch">
<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"  name="newsletter_status" value="Yes"  @if($user->newsletter_status=="Yes") checked @endif >
<label class="form-check-label" for="flexSwitchCheckChecked">Newsletter Emails</label>
</div></div>
</div>

                  
                </div>
                <button type="submit" class="btn-right">submit</button>
              </div>
            </div>
          </div>
		  </form>
		  
		  
		  
		  
		  
		  <div class="card answer">
            <div class="row">
            
            <div class="col-lg-12"><h3>Change Password </h3></div>
            </div>
            <form action="{{ route('user.pw.reset') }}" method="POST">
                @csrf
				  <input type="hidden" name="email" value="{{ Auth::guard('user')->user()->email}}">
            <div class="row">
            
            
            
                 <div class="col-lg-4 col-12">
                      <div class="form-group">
                        <label>Current password:</label>
                      <input name="oldPassword" type="password" class="form-control{{ $errors->has('oldPassword') ? ' is-invalid' : '' }}"  placeholder="" value="">
                     @if ($errors->has('oldPassword'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('oldPassword') }}</strong>
                            </span>
                        @endif
                      </div>
                    </div>
                    <div class="col-lg-4 col-12">
                      <div class="form-group">
                       <label>New password:</label>
                      <input value="" id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">

                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                      </div>
                    </div>
                    
                      <div class="col-lg-4 col-12">
                      <div class="form-group">
                         <label>Confirm new password:</label>
                      <input  id="password-confirm" type="password" class="form-control" name="password_confirmation" >
                      </div>
                    </div>
                  <div class="col-lg-4 col-12"> <button type="submit" class="bl-btn">update password</button> </div>
				   </div>
                   </form>
                  
            </div> 
		  
		  
		  
        </div>
		
		
		
		 
		
		
		
		
		
		
		
		
		
		
      </div>
	  
	  
	  
	  
	  
	  
	 
          
	  
	  
	  
	  
	  
	  
    </div>
  </div>
</sectiion>
<script type="text/javascript">
//var $fmcg = $.noConflict();
    $fmcg( document ).ready(function() {
        $fmcg('#upload_image').click(function(){  
            $fmcg('#imgupload').trigger('click'); 
        });


$fmcg(".image").change(function(e) { 
var data = new FormData();
data.append('image', $fmcg('input[type=file]')[0].files[0]);
data.append('_token', "{{ csrf_token() }}");
$fmcg.ajax({
        url:'{{route('update-image')}}',
        type: 'POST',
        data : data,
        enctype : 'multipart/form-data',
        contentType: false,
        processData: false,
        success: function( data ) { 
            var baseUrl = "{{asset('')}}";
            var imageUrl = baseUrl + data.image_path;
            $fmcg('.prof_img').attr('src', imageUrl);
          //  $fmcg('#changeimage').html('<img src="'+ imageUrl +'" height="120px" width="150px">');
        },
        error: function() {
            //alert('Upload Failed');
        }
   });   
});


});
    </script>

@endsection