@extends('layouts.template')
@section('title')
FMCG | LOGIN
@endsection
@section('content')
<div class="bg-light min-vh-100 d-flex flex-row align-items-center login-scr">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 ">
          <?php
          
           $message='';
           if(Session::has('message'))
           {     
                $data=explode(':',Session::get('message'));
                if(count($data)>0)
                {
                    if($data[0]!="Your password has been changed!") {
                    $message=$data[0]; 
                    $email_id=$data[1];
                    }
                }
           }
          
           ?>
           @if($message=='Email_not_verified') 
           <div class="modal email-modal" id="Mymodal" tabindex="-1" aria-labelledby="MymodalLabel1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="MymodalLabel1" style="text-align:center;">Verify Your Email</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="hidepopemailverify()">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          
          
          <div class="modal-txt"></div>
          <p>We sent an email to {{$email_id}}</br>
          Click the link inside to get started.</p>
         
          
           <div class="gmail-c"> <a href="https://accounts.google.com/signin/v2/identifier" target="_blank"><img src="{{asset('images/gmail.png')}}"  /><b>    Open Gmail</b></a></div>
           <br>
           <form method="POST" action="{{ route('resend_veryfication_email') }}">
            @csrf
            <input type="hidden" name="email_id" value="{{$email_id}}">
            <center><input type="submit" name="" value="Resend Email">   </center>
           </form>
              </div>

      </div>
      <div class="modal-footer">
       
      </div>
    </div>
  </div>
</div>

           @elseif(Session::has('message')) <div class="alert alert-info">{{Session::get('message') }}</div> @endif
           
           
           
           
           
           
           
           
            
           
           
           
           
           
           
           
           
           
           
        <div class="loginC align-middle">
          <div class="row">
            <div class="col-lg-6 col-12 ">
              <div class="log-left ">
              
            <div class="log-left-content">
              <div class="log-logo"><img src="{{URL::asset('/images/footer-logo.png')}}"></div>
              	<h1>Welcome Back !</h1>
              </div>
              
              </div>
            </div>
            <div class="col-lg-6 col-12">
              <div class="log-right">
              
              <h2>Sign In</h2>
              
            
                 
                  <div class="card-body">
                    @isset($url)
                    <form method="POST" action='{{ url("login/$url") }}' aria-label="{{ __('Login') }}">
                    @else
                    <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                    @endisset
                        @csrf

                        <div class="form-group">
                    <label>Email Address</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"  required >

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                        </div>

                 <div class="form-group">
                    <label>Password</label>
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                </div>

                        

                        <div class="frg-pw"><a href="{{ route('forget.password.get' ) }}">Forgot password?</a></div>
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                
                            
                    </form>
                </div>
                  <div class="n-reg">Don't have an account? <a href="{{route('user-register')}}">sign up now</a></div>
                  
                  
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
//     var modal = document.getElementById("Mymodal");
//     modal.style.display = "block";
// function hidepopemailverify(){
//     var modal = document.getElementById("Mymodal");
//     modal.style.display = "none";
// }
</script>
@endsection
