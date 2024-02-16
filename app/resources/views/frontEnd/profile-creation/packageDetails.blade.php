@extends('layouts.template')
@section('title', 'Package Details')
@section('content')
<div class="pack-listing pack-dtl">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-12 ">
        <div class="card-bx plan-dtl-bx">
        <div class="pl-tp">
          <h1> {{$package->name  ?? ''}}<br/>
<span>({{$package->user_type  ?? ''}})</span></h1>
 @if(($package->package_offer_price != "") || ($package->package_offer_price>0))
	 <h2> <b>(<del>${{$package->package_basic_price}}</del></small>&nbsp;<small>$</small>{{$package->package_offer_price}})</b> ({{$package->package_validity}}) </h2>
 @else
     <h2> <b>(${{$package->package_basic_price ?? ''}})</b> ({{$package->package_validity}}) </h2>
 @endif
            </div>
          <div class="pans-txt">
            <p>{{$package->package_description ?? ''}}</p>
          </div>
         <ul>
                @foreach ($package->IncludedFeatures as $includ_feature)
                        @if($includ_feature->feature!="")
                        <li class="avl">{{ $includ_feature->feature }}</li>
                        <p></p>
                       @endif
                        @endforeach 
                        @foreach ($package->ExcludedFeatures as $exclud_feature)
                        @if($exclud_feature->feature!="")
                        <li class="n-avl">{{ $exclud_feature->feature }}</li>
                       @endif
                        @endforeach
                </ul>
                <div class="price-footer"> 
		            <a  class="default-btn" href="{{route('subscription.checkout', ['pkg_id'=>$package->id, 'accounts_id'=>$accounts_id])}}" >subscribe</a>
		        </div>
        </div>
      </div>
      <div class="col-lg-6 col-12 ">
        <div class="card-bx pack-dtl-right">
        <div class="plan-img"><img src="{{ URL::asset('/images/plan.jpg')}}"></div>
        @if(!empty($lowest))
          <h2> Plans starting at <b>${{$lowest->package_basic_price}} </b></h2>
          <p>{{$lowest->package_description}}  </p>
          <a href="{{route('package.listing')}}" class="default-btn">View more plans</a>
          @endif
          </div>
      </div>
    </div>
  </div>
</div>
@endsection