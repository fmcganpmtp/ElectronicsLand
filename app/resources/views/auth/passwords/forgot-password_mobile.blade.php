@extends('layouts.template_mobile')
@section('title','FMCG | Forgot Password')
@section('content')
<div class="inner-page-C">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 ">
        <div class="login-page">
          <h2>Forgot Password ?</h2>
          <div class="formC"> @if(Session::has('message'))
            <p>{{Session::get('message')}}</p>
            @endif
            <form method="post" action="{{route('forget.password.post')}}">
              @csrf
              <div class="form-group">
                <div class="frm-out">
                  <input class="form-control {{ $errors->has('email')? ' is-invalid':''}}" type="email" name="email" placeholder="Enter your email Id">
                  @if ($errors->has('email')) <span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('email') }}</strong> </span> @endif <i class="fa fa-envelope-o" aria-hidden="true"></i> </div>
              </div>
              <button type="submit" class="yellow-btn">submit</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection 