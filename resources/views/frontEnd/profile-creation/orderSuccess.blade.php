@extends('layouts.template')
@section('title', 'Order | Success')
@section('content')

<div class="bg-light  d-flex flex-row align-items-center login-scr sub-chk-out pack-bg">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 ">
        <div class="success-bx">
          <div class="card-bx">
            <div class="success-img"><img src="assets/images/check-icon.jpg"></div>
            <h3>You have successfully <b>subscribed</b></h3>
            <p>May your journey in FMCG Land be filled with success, growth, and fulfillment.</p>
			<p>Best of luck,and may the deals be with you! </p>
          <!--  <a href="{{route('home')}}" class="default-btn">Proceed</a>-->
            @if(Auth::guard('user')->user()->usertype== "seller") <a href="{{route('ViewProfileSeller')}}" class="default-btn">Continue</a>@endif
            @if(Auth::guard('user')->user()->usertype== "buyer") <a href="{{route('ViewProfileBuyer')}}" class="default-btn">Continue</a>@endif
             </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
        $(document).ready(function () {
          
           //     location.reload(true);
          
        });
    </script>
@endsection
