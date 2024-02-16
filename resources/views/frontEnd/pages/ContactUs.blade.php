@extends('layouts.template')
@section('title')
FMCG | Contact Us
@endsection
@section('content')



<div class="page-title-area">
  <div class="container">
    <div class="page-title-content">
      <ul>
        <li> <a href="{{ route('home') }}"> Home </a> </li>
        <li class="active">Contact</li>
      </ul>
    </div>
  </div>
</div>
<div class=" container contact-page ptb-54">
  
  
  
  <div class="row wow fadeInUp">
  
   <div class="col-lg-12">
  <div class="contact-form">
                  <h3>Contact us</h3>
                  <p style="color:black!important">Question ?<br>
                    Please reach out to : <a href="mailto:info@fmcgland.com" style="color:black!important">info@fmcgland.com</a></p>
<!--                  @if(Session::has('message'))-->
<!--<p class="alert {{ Session::get('success-class', 'alert-success') }}">{{ Session::get('message') }}</p>-->
<!--@endif-->
<!--				  <form method="post" action="{{ route('contact.submit') }}" >-->
<!--                    @csrf-->
<!--                  <div class="row">-->
				  
				  
				  
<!--                  <div class="col-lg-4 col-12">-->
<!--                      <div class="form-group">-->
<!--                        <label>Name *</label>-->
<!--                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Enter your name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}">-->
<!--                        @if ($errors->has('name'))-->
<!--                                    <span class="invalid-feedback" role="alert">-->
<!--                                        <strong>{{ $errors->first('name') }}</strong>-->
<!--                                    </span>-->
<!--                        @endif-->
<!--                    </div>-->
<!--                    </div>-->
<!--                    <div class="col-lg-4 col-12">-->
<!--                      <div class="form-group">-->
<!--                        <label>Email * </label>-->
<!--                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}">-->
<!--                        @if ($errors->has('email'))-->
<!--                                    <span class="invalid-feedback" role="alert">-->
<!--                                        <strong>{{ $errors->first('email') }}</strong>-->
<!--                                    </span>-->
<!--                                @endif-->
<!--                    </div>-->
<!--                    </div>-->
<!--                    <div class="col-lg-4 col-12">-->
<!--                      <div class="form-group">-->
<!--                        <label>Phone * </label>-->
<!--                        <input type="text" id="review-title" name="phone" value="{{ old('phone') }}" placeholder="Enter your phone number" class="form-control {{ $errors->has('phone') ? ' is-invalid' : '' }}">-->
<!--                        @if ($errors->has('phone'))-->
<!--                                    <span class="invalid-feedback" role="alert">-->
<!--                                        <strong>{{ $errors->first('phone') }}</strong>-->
<!--                                    </span>-->
<!--                                @endif-->
<!--                    </div>-->
<!--                    </div>-->
<!--                    <div class="col-lg-12 col-12">-->
<!--                      <div class="form-group">-->
<!--                        <label>Message * </label>-->
<!--                        <textarea name="message" id="review-body" rows="4" placeholder="Message" class="form-control {{ $errors->has('message') ? ' is-invalid' : '' }}">{{ old('message') }}</textarea>-->
<!--                        @if ($errors->has('message'))-->
<!--                                    <span class="invalid-feedback" role="alert">-->
<!--                                        <strong>{{ $errors->first('message') }}</strong>-->
<!--                                    </span>-->
<!--                                @endif-->
<!--                    </div>-->
<!--                    </div>-->
<!--                    <div class="col-lg-12">-->
<!--                      <button type="submit" class="btn default-btn">Submit</button>-->
<!--                    </div>-->
					
					
					
<!--                  </div>-->
<!--				  </form>-->
				  
                </div>
  
  </div></div>
  </div>


@endsection
