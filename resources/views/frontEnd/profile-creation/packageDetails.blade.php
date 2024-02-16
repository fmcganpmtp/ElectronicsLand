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

 @if(($package->package_offer_price != "") && ($package->package_offer_price>0))
	 <h2> <b>(<del>&euro; {{$package->package_basic_price}}</del></small>&nbsp;<small>&euro; </small>{{$package->package_offer_price}})</b> ({{$package->package_validity}}) </h2>
 @else
     <h2> <b>(&euro; {{$package->package_basic_price ?? ''}})</b> ({{$package->package_validity}}) </h2>
 @endif
            </div>
          <div class="pans-txt">
            <p>{{$package->package_description ?? ''}}</p>
          </div>
        <!-- <ul>
               {{-- @foreach ($package->IncludedFeatures as $includ_feature)
                        @if($includ_feature->feature!="")
                        <li class="avl">{{ $includ_feature->feature }}</li>
                        <p></p>
                       @endif
                        @endforeach 
                        @foreach ($package->ExcludedFeatures as $exclud_feature)
                        @if($exclud_feature->feature!="")
                        <li class="n-avl">{{ $exclud_feature->feature }}</li>
                       @endif
                        @endforeach --}}
                </ul>-->
                <div class="pkg-det32" >
                    <h2 class="pkg-det-outhead">GENERAL</h2>
                <ul>
                    <li><label class="pkg-det32-title"><span>Access to Company Profile</span><b>:</b></label><label class="pkg-det32-ans"> {{ $package->general_companyProfile==1?'Yes':'No' }}</label></li>
                    <li><label class="pkg-det32-title"><span>Access to Company Dashboard</span><b>:</b></label><label class="pkg-det32-ans"> {{ $package->general_companyDashboard==1?'Yes':'No' }}</label></li>
                    <li><label class="pkg-det32-title"><span>Access to Profile Insights</span><b>:</b></label><label class="pkg-det32-ans"> {{ $package->general_profileInsights==1?'Yes':'No' }}</label></li>
                    <li><label class="pkg-det32-title"><span>Access to Media Options</span><b>:</b></label><label class="pkg-det32-ans"> {{ $package->general_accesToMedia==1?'Yes':'No' }}</label></li>
                    
                </ul>
                   <h2 class="pkg-det-outhead">MARKETPLACE</h2>
                <ul>
                    <li><label class="pkg-det32-title"><span>Access to Search & find products</span><b>:</b></label><label class="pkg-det32-ans"> {{ $package->market_Search==1?'Yes':'No' }}</label></li>
                    <li><label class="pkg-det32-title"><span>Product Uploads</span><b>:</b></label><label class="pkg-det32-ans"> 
                    <?php 
                        if($package->market_uploads!='') 
                        echo $package->market_uploads;
                        else
                        { ?>
                            <strong>Unlimited</strong> 
                        <?php } ?>
                    </label></li>
                    <li><label class="pkg-det32-title"><span>Access to View Product Requests</span><b>:</b></label><label class="pkg-det32-ans"> {{ $package->market_productRequests==1?'Yes':'No' }}</label></li>
                    <li><label class="pkg-det32-title"><span>Access to Create Product Requests</span><b>:</b></label><label class="pkg-det32-ans"> {{ $package->market_createProductRequests==1?'Yes':'No' }}</label></li>
                    
                </ul>
                <h2 class="pkg-det-outhead">NETWORK FEED</h2>
                <ul>
                    <li><label class="pkg-det32-title"><span>Allow Expand your Network</span><b>:</b></label><label class="pkg-det32-ans"> {{ $package->network_expand==1?'Yes':'No' }}</label></li>
                    <li><label class="pkg-det32-title"><span>Allow Chat with Network</span><b>:</b></label><label class="pkg-det32-ans"> {{ $package->network_chat==1?'Yes':'No' }}</label></li>
                    
                </ul>
                </div> 
                <div class="price-footer"> 
		            <a  class="default-btn" href="{{route('subscription.checkout', ['pkg_id'=>$package->id, 'accounts_id'=>$accounts_id])}}" >subscribe</a>
		        </div>
        </div>
      </div>
      <div class="col-lg-6 col-12 ">
        <div class="card-bx pack-dtl-right">
        <div class="plan-img"><img src="{{ URL::asset('/images/plan.jpg')}}"></div>
        @if(!empty($lowest))
          <h2> Plans starting at <b>&euro; {{$lowest->package_basic_price}} </b></h2>
          <p>{{$lowest->package_description}}  </p>
          <a href="{{route('package.listing')}}" class="default-btn">View more plans</a>
          @endif
          </div>
      </div>
    </div>
  </div>
</div>
@endsection