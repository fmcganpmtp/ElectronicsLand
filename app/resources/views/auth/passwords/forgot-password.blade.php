@extends('layouts.template')
@section('title','FMCG | Forgot Password')
@section('content')
<div class="bg-light min-vh-100 d-flex flex-row align-items-center login-scr">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 ">
        <div class="loginC  frg-pwd-c">
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
              
                            <h2>Forgot Password ?</h2>

              @if(Session::has('message'))
              <p>{{Session::get('message')}}</p>
              @endif
                <form method="post" action="{{route('forget.password.post')}}">
                  @csrf
                  <div class="form-group">
                    <label>Enter your email Id</label>
                    <input class="form-control {{ $errors->has('email')? ' is-invalid':''}}" type="email" name="email">
                    @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                  </div>
                  
                  
                  
                  
                  <button type="submit">submit</button>
                  
                  
                  
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
