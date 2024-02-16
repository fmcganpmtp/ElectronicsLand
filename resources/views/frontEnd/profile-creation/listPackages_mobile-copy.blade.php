@extends('layouts.template_mobile')
@section('title', 'List Packages')
@section('content')
<div class="bg-light min-vh-100 d-flex flex-row align-items-center pack-listing">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="pack-top wow fadeInUp ">
          <h2> Choose Your Perfect Match with FMCG Land's Subscription Packages!</h2>
          <h3>Two subscription packages, Standard (Buyer) and Premium (Buyer/Seller), allowing you to select the package that fits your needs. Whether you are a buyer looking for quality products or a seller looking for a platform to showcase your goods and expand your network, we have got you covered.</h3>
        </div>
        <?php   
$std_count = $packages->where('subscription_type', 'Standard')->count(); 
$extended_count = $packages->where('subscription_type', 'Extended')->count(); 
$extended_class = "";
// add class to extended if standard = 0
if($std_count==0 && $extended_count==0) echo "<div class='green'><h4>There are no more plans to upgrade.</h4></div>";
?>
<ul class="nav nav-pills mb-3 animated_tab" id="pills-tab" role="tablist">
@if ($std_count>0) 
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Standard </button>
  </li>
  @endif
  @if ($extended_count>0)
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Extended </button>
  </li>
  @endif
</ul>
<div class="tab-content" id="pills-tabContent">
@if ($packages->where('subscription_type', 'Standard')->count()>0)
  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
  <div class="pack-prim-out">
          <div class="row">
		  <?php $count = 0; ?>
		  @foreach ($packages as $package)  
          @if($package->subscription_type=="Standard")
            <div class="col-lg-6 col-md-6">
		<?php if(++$count % 2) $cls="iq-pricing mb-4 mb-lg-0 text-center  green-hover";
			  else $cls="iq-pricing mb-4 mb-lg-0 text-center  org-hover"; ?>
              <div class="{{$cls}}">
                  
                  <div class="price-table-top">
                <div class="price-title iq-parallax">
                  <div class="month-circle">
                    <div class="month-price">
					@if(($package->package_offer_price != "") || ($package->package_offer_price>0))
						<h2><small><del>${{$package->package_basic_price}}</del></small><br><small>$</small>{{$package->package_offer_price}}<small></small></h2>
						@else
                      <h2><small>$</small>{{$package->package_basic_price}}<small></small></h2>
				  @endif
                      <h6>{{$package->package_validity}}</h6>
                    </div>
                  </div>
                  <div class="main-title">
                    <h2 class="title">{{$package->name}} <br/>
                      <span>({{$package->user_type}})</span> </h2>
                  </div>
                   @if(!empty(Session::get('last_oreder_total')))
                    <p class="pkg_discount">Previous Package Discount : {{Session::get('last_oreder_total')}}</p>
                    @endif
                </div>
                
                </div>
                
               <div class="package_outer_div">
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
                </div>
                @if($stripe_status=="Enabled")
                <form action="{{route('package.details')}}" method="get">
                @else
                <!--  <form action="{{route('package.details1')}}" method="get">-->
              <form method="post" action="{{route('cart.submit')}}" name="direct_submit">
                @endif     
                @csrf
                <input type="hidden" name="extended" value="no">
                <input type="hidden" name="accounts_id" value="">
                <input type="hidden" name="package_id" value="{{$package->id}}">
                <div class="price-footer"><input style="border:0 none !important;" type="submit" class="button" value="Subscribe" name="submit"></div>
                </form> </div>
                
                
                
            </div>
			@endif
		  @endforeach
          </div>
           </div>
  </div>
  @endif
  @if ($packages->where('subscription_type', 'Extended')->count()>0)
  <div class="tab-pane fade <?=$extended_class?>" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
  <div class="pack-prim-out">
          <div class="row">
		  <?php $count = 0; ?>
      <div style="color:#ff0000;text-align:center;margin:0 auto;position:relative;top: -20px;">{{ $errors->first('accounts_id') }}</div>
		  @foreach ($packages as $package)  
          @if($package->subscription_type=="Extended")
            <div class="col-lg-6 col-md-6">
		<?php if(++$count % 2) $cls="iq-pricing mb-4 mb-lg-0 text-center  green-hover";
			  else $cls="iq-pricing mb-4 mb-lg-0 text-center  org-hover"; ?>
              <div class="{{$cls}}">
                <div class="price-title iq-parallax">
                  <div class="month-circle">
                    <div class="month-price">
                      @if(($package->package_offer_price != "") || ($package->package_offer_price>0))
						<h2><small><del>${{$package->package_basic_price}}</del></small><br><small>$</small>{{$package->package_offer_price}}<small></small></h2>
						@else
                      <h2><small>$</small>{{$package->package_basic_price}}<small></small></h2>
				  @endif
                      <h6>{{$package->package_validity}}</h6>
                    </div>
                  </div>
                  <div class="main-title">
                    <h2 class="title">{{$package->name}}<br/>
                      <span>({{$package->user_type}})</span> </h2>
                  </div>
                   @if(!empty(Session::get('last_oreder_total')))
                    <p class="pkg_discount">Previous Package Discount : {{Session::get('last_oreder_total')}}</p>
                    @endif
                </div>
                <div class="package_outer_div">
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
                <div class="dropdown drpbuttonli">
                    <button class="btn btn-secondary dropdown-toggle p1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Choose no of profile
                    </button>
                    <ul class="dropdown-menu d1">
                      @foreach ($package->PackageAccount as $package_account)
                          <li id="{{$package_account->id}}" data-id="{{$package->id}}" class="account_li"><a class="dropdown-item">{{$package_account->no_of_accounts}} profile, 
                          @php
                          $original_price = $package_account->cost_per_account;
                          $selling_price = $package_account->per_account_offer_price; @endphp
                          @if($selling_price!="" && $selling_price >0)
                          @if(($original_price!=0 || $original_price!=""))
                          @php $offer=(($original_price-$selling_price)/$original_price)*100;  @endphp
                          @endif
                          {{round($offer, 2)}}% off, <span>${{$selling_price}} </span> / account
                             @else <span>${{$original_price}} </span> / account @endif
                          </a>
                        </li>
                      @endforeach
                   </ul>
                </div>
                </div>
                @if($stripe_status=="Enabled")
                <form action="{{route('package.details')}}" method="get">
                @else
               <!--  <form action="{{route('package.details1')}}" method="get">-->
              <form method="post" action="{{route('cart.submit')}}" name="direct_submit">
                @endif  
                  @csrf
                  <input type="hidden" name="extended" value="yes">
                <input type="hidden" class="accounts_id{{$package->id}}" name="accounts_id" value="">
                <input type="hidden" name="package_id" value="{{$package->id}}">
                <input type="hidden" name="package_acc" value="">
                <div class="price-footer"><input style="border:0 none !important;" type="submit" class="button" value="Subscribe" name="submit"></div>
              </form>
              </div>
            </div>
			@endif
            @endforeach
          </div>
           </div> 
  </div>
  @endif
</div>
        </div>     
    </div>
  </div>
</div>
<?php
if($std_count==0 && $extended_count>0 || !empty($errors->first('accounts_id'))) 
{ ?>
  <script> 
  $fmcg(function() {
     $fmcg('#pills-profile-tab').addClass('active');
     $fmcg('#pills-profile').addClass('active show');
     $fmcg('#pills-home-tab').removeClass('active');
     $fmcg('#pills-home').removeClass('active show');
    });
  </script>
  <?php
  $extended_class = " show active"; 
}
?>
@endsection

@section('footer_script')

<script> 
$fmcg(function(){
  $fmcg(".account_li").click(function(){ 
      var accounts_id = $fmcg(this).attr('id');
      var package_id =$fmcg(this).attr('data-id');
      $fmcg('.accounts_id'+package_id).val(accounts_id);
      $fmcg(this).parents('.drpbuttonli').find('.p1').text($fmcg(this).find('a').text()); 
      $fmcg(this).parents('.drpbuttonli').find('.p1').val($fmcg(this).find('a').text()); 
   });
});
</script>
@endsection