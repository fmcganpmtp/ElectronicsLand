@extends('layouts.template_mobile')
@section('title')
FMCG | Register
@endsection
@section('content')
<div class="inner-page-C">
   <div class="container">
      <div class="row">
         <div class="col-lg-12 ">
                        @if(Session::has('message')) <div class="alert alert-info">{{Session::get('message') }}</div> @endif
        <div class="login-page">
            
                        <h2>Create Account</h2>
          <div class="formC acc-create">
                           @isset($url)
                           <form method="POST" action='{{ url("register/$url") }}' aria-label="{{ __('Register') }}" enctype='multipart/form-data'>
                              @else
                           <form method="POST" action="{{ route('register') }}" aria-label="{{ __('Register') }}" enctype='multipart/form-data'>
                              @endisset
                              @csrf
                              
                               <div class="form-group">
                                
                                 <input id="company_name" type="text" class="form-control{{ $errors->has('company_name') ? ' is-invalid' : '' }}" placeholder="Company Name*" name="company_name" value="{{ old('company_name') }}" >
                                 @if ($errors->has('company_name'))
                                 <span class="invalid-feedback" role="alert">
                                 <strong>{{ $errors->first('company_name') }}</strong>
                                 </span>
                                 @endif
                              </div>
                              
                              
                              <div class="form-group">
							 


                                 <div>
                                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" placeholder="Name*"  value="{{ old('name') }}">
                                    @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                 </div>
                              </div>
                              <div class="form-group">
                                 <input id="surname" type="text" class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" placeholder="Surname*"  value="{{ old('surname') }}" >
                                 @if ($errors->has('surname'))
                                 <span class="invalid-feedback" role="alert">
                                 <strong>{{ $errors->first('surname') }}</strong>
                                 </span>
                                 @endif
                              </div>
                              <div class="form-group">
                                 <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="Email*" value="{{ old('email') }}" >
                                 @if ($errors->has('email'))
                                 <span class="invalid-feedback" role="alert">
                                 <strong>{{ $errors->first('email') }}</strong>
                                 </span>
                                 @endif
                              </div>
                              <div class="form-group">
                                 
                                 <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" maxlength="15" name="full" placeholder="Phone number*" value="{{ old('phone') }}" >
                                 <span  role="alert">
								 @if($errors->has('phone'))
                                 <strong class="error" style="font-size: .875em; color: #dc3545;">{{ $errors->first('phone') }}</strong>
                                 @endif
								 </span>
                              </div>
							 
                              <div class="form-group">
                                <!-- <label for="password" >{{ __('Password') }}<sup>*</sup></label>-->
                                 <input id="password" type="password" class="pr-password  form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Password"  >
                                 @if ($errors->has('password'))
                                 <span class="invalid-feedback" role="alert">
                                 <strong>{{ $errors->first('password') }}</strong>
                                 </span>
                                 @endif
                              </div>
                              <div class="form-group">
                                 <!--  <label for="password-confirm" >{{ __('Confirm Password') }}<sup>*</sup></label>-->
                                 <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password*" >
                              </div>
                              <div class="form-group">
                                 <div class="tms">
                                    <label> Terms & conditions and consents<sup>*</sup>  </label>
                                    <div class="form-check">
                                       <input class="form-check-input " name="agree" type="checkbox" value="" id="flexCheckDefault2"  required>
                                       <label class="form-check-label" for="flexCheckDefault"> I accept <a href="{{ route('terms.condition') }}" target="_blank">Terms and Conditions </a></label>
                                       
                                    </div>
									
									<div class="form-check">
                                       <input class="form-check-input" name="agree_privacy_policy" type="checkbox" value="" id="flexCheckDefault2" required>
                                       <label class="form-check-label" for="flexCheckDefault"> I accept <a href="{{ route('privacy.policy') }}" target="_blank"> Privacy Policy.</a> </label>
                                       
                                      
                                    </div>
                                 </div>
                              </div>
                              <div class="form-group mb-0">
                                 <div class="">
                                    <button type="submit" id="register" class="yellow-btn">
                                    {{ __('Register') }}
                                    </button>
                                    <div class="sgn-up">Already have an account? <a href="{{route('user-login')}}">sign in</a> now</div>
                                 </div>
                              </div>
                           </form>
                  
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<link rel="stylesheet" href="{{ asset('/css/intlTelInput.css')}}">
<link rel="stylesheet" href="{{ asset('/css/jquery.passwordRequirements.css')}}">
<script src="{{ asset('js/intlTelInput.min.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{ asset('js/jquery.passwordRequirements.js')}}"></script>
<script src="{{ asset('js/jquery.passwordRequirements.min.js')}}"></script>
<script>
        /* trigger when page is ready */
        var $ = jQuery;
                (function($) {
        $(document).ready( function () {
            $(".pr-password").passwordRequirements({

});
        });
      })(jQuery);


        
    </script>
<script>
   var input = window.intlTelInput(document.querySelector("#phone"), {
   separateDialCode: true,
   preferredCountries:["nl"],
   hiddenInput: "phone",
   utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
   });
   
   (function(jQuery) {
     jQuery(document).ready( function () {
     jQuery('#register').on( "click", function() {
   var full_number = input.getNumber(intlTelInputUtils.numberFormat.E164);
   $("input[name='phone[full]'").val(full_number);
   
   });
   });
   })(jQuery);
         
</script>
@endsection