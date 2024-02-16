@extends('layouts.template_mobile')
@section('title','FMCG | Reset Password')
@section('content')
<div class="inner-page-C">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 ">
        <div class="login-page">
          <h2>Reset Password ?</h2>
          <form action="{{ route('reset.password.post') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group">
              <div class="frm-out">
                <input type="text" id="email_address" class="form-control" autocomplete="off" name="email" required placeholder="E-Mail Address" >
                @if ($errors->has('email')) <span class="text-danger">{{ $errors->first('email') }}</span> @endif <i class="fa fa-envelope-o" aria-hidden="true"></i> </div>
            </div>
            <div class="form-group">
              <div class="frm-out">
                <input type="password" id="password" class="form-control pr-password" autocomplete="off" name="password" required placeholder="Password" >
                @if ($errors->has('password')) <span class="text-danger">{{ $errors->first('password') }}</span> @endif <i class="fa fa-lock" aria-hidden="true"></i></div>
            </div>
            <div class="form-group">
            
               <div class="frm-out">
                <input type="password" id="password-confirm" class="form-control" name="password_confirmation" required placeholder="Confirm Password" >
                @if ($errors->has('password_confirmation')) <span class="text-danger">{{ $errors->first('password_confirmation') }}</span> @endif <i class="fa fa-lock" aria-hidden="true"></i></div>
            </div>
            <div class="col-md-12 offset-md-4">
              <button type="submit" class="btn yellow-btn"> Reset Password </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="{{ asset('/css/jquery.passwordRequirements.css')}}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{ asset('js/jquery.passwordRequirements.js')}}"></script>
<script src="{{ asset('js/jquery.passwordRequirements.min.js')}}"></script>
<script>
      var $ = jQuery;
      (function($) {
        $(document).ready( function () {
            $(".pr-password").passwordRequirements({});
      });
      })(jQuery);    
    </script>
@endsection 