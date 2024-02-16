@extends('layouts.template_mobile')
@section('title', 'List Packages')
@section('content')
<?php
$offer_price = $package->package_offer_price ?? "";
if ($offer_price != "" || $offer_price > 0) {
    $basic_price = $package->package_offer_price;
} else {
    $basic_price = $package->package_basic_price;
}
if ($package->subscription_type == "Extended") { 
    $account_offer_price = $accounts->per_account_offer_price ?? "";
    if ($account_offer_price != "" || $account_offer_price > 0) {
        $account_basic_price = $account_offer_price;
    } else {
        $account_basic_price = $accounts->cost_per_account ?? "";
    }
    $no_of_accounts =1;
    if(!empty($accounts->no_of_accounts))
    $no_of_accounts = $accounts->no_of_accounts;
    $profile_cost = $account_basic_price * $no_of_accounts;
    $grand_total = $basic_price + $profile_cost;

    if (!empty(Session::get("last_oreder_total"))) {
        $grand_total =$basic_price + $profile_cost - Session::get("last_oreder_total");
    }
} else {
    $no_of_accounts = $account_basic_price = $profile_cost = "";
    $grand_total = $basic_price;
    if (!empty(Session::get("last_oreder_total"))) {
        $grand_total = $basic_price - Session::get("last_oreder_total");
    }
}
if ($grand_total < 0) {
    $grand_total = 0;
}
?>	  
<div class="pack-listing pack-dtl">
   <div class="container">
      <div class="row">
         <div class="col-lg-6 col-12 ">
            <div class="card-bx plan-dtl-bx">
               <div class="pl-tp">
                  <h1> {{$package->name  ?? ''}}<br/>
                     <span>({{$package->user_type  ?? ''}})</span>
                  </h1>
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
               <!--href="subscription-checkout.php"-->
               <div class="price-footer">
                  <!-- <a  class="default-btn" href="{{route('subscription.checkout', ['pkg_id'=>$package->id, 'accounts_id'=>$accounts_id])}}" >subscribe</a>-->
                  <form method="post" action="{{route('cart.submit')}}">
                     @csrf
                     <input type="hidden" name="package_id" value="{{ $package->id ?? '' }}">
                     <input type="hidden" name="accounts_id" value="{{ $accounts->id ?? '' }}">	
                     <input type="hidden" name="order_total" value="<?= $grand_total ?>">
                     <button class="default-btn" type="submit">Subscribe</button>
                  </form>
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