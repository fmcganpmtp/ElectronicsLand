@extends('layouts.template')
@section('title', 'List Packages')
@section('content')
<div class="bg-light min-vh-100  align-items-center pack-listing package-new-out">
  <!--new-pack-style-->
   
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="pack-box-03">
          <div class="top-title">
            <h2>Select Your Plan on FMCG Land</h2>
          </div>
          <div class="pack-sec-1">
            <div class="row">
              <div class="col-lg-6 col-12">
                <h3>You are just one step away from the best the FMCG industry has to offer!</h3>
                <div class="pack-top-content">
                  <p>At FMCG Land we aim to deliver you the best the FMCG industry has to offer, with a personal plan for everybody. From Premium to Free. Get instant access to the leading B2B platform connecting the global FMCG industry. With networking, job postings, and trade show information beyond buying and selling. Grow your business in a comprehensive ecosystem where everyone profits.</p>
                  <p class="top-padding50">Need any help? Contact us at: <a href="mailto:support@fmgland.com">support@fmgland.com</a></p>
                </div>
                <a href="#" class="yellow-btn03">START YOUR PERSONAL PLAN NOW</a> </div>
              <div class="col-lg-6 col-12">
                <div class="pack-top-img"><img src="assets/images/pack-top-image.jpg"></div>
              </div>
            </div>
          </div>
          <!--sec1-->


          <div class="sectioon-2-table">
            <div class="row">
              <div class="col-lg-12">
                <div class="pack-table">
                    <?php $count = $packages->count(); 
                          if($count>0) {
                    
                    ?>
<table class="table packageListCoverTable"  cellpadding="0" cellspacing="0">
    <tr>
        <td class="out-tbl" style="margin:0px 0px; padding:0px 0px; ">
            <table class="table table-bordered">
                
                <thead>

   <tr class="">
                    <th scope="col" > <div class="th-border"></div></th>  
					@foreach ($packages as $package)
        
                    <th scope="col"> <div class="th-border"></div>
                      <div class="pack-tble-th">
                        <h4>{{$package->name}} ({{$package->package_validity}})</h4>
                        <div class="dip-tbl">
                          <?php 
                          $validity=12;
                          if($package->package_validity=='One year')
                          $no_of_months = 12;
                          if($package->package_validity=='6 months')
                          $no_of_months = 6;
                          if($package->package_validity=='3 months')
                          $no_of_months = 3;
                          
                          ?>
                          <?php 
                          $price=  $package->package_basic_price/$no_of_months;
                          $price=explode('.',number_format((float)$price, 2, '.', ''));
                          $price_offer=  $package->package_offer_price/$no_of_months;
                          $price_offer=explode('.',number_format((float)$price_offer, 2, '.', '')); ?>
                          @if(($package->package_offer_price != "") && ($package->package_offer_price>0))
                          
                          <span class="del-prc1">
                          <h5><span class="pak-cur">&euro;</span> <del>{{$price[0]}}</del></h5>
                          <h6><b><del>{{$price[1]}}</del></b><br />
                         <span>/pm</span></h6>
                          </span>
                          
                          <h5><span class="pak-cur">&euro;</span> {{$price_offer[0]}}</h5>  
                          <h6><b>{{$price_offer[1]}}</b><br />
                          <span>/pm</span></h6>
                          
                          @else
                          <h5><span class="pak-cur">&euro;</span> {{$price[0]}}</h5>
                          <h6><b>{{$price[1]}}</b><br />
                         <span>/pm</span></h6>
                          
                          
                          @endif
                            
                            
                            
                        </div>
                      </div></th>
                    @endforeach
                  </tr>
</thead>

                
                <tbody>
               
				  <tr class="gray-sel">
                    <td><b>General</b></td>
					@foreach ($packages as $package)
        
                    <?php $decsrpn = (strlen($package->package_description) > 200) ? substr($package->package_description,0,200).'...' : $package->package_description; ?>
                    <td><div class="pkg-descr">{{$decsrpn}}</div></td>
                    @endforeach
					</tr>
                  <tr>
                    <td>Company Profile</td>
					@foreach ($packages as $package)
        
                    <td><div class="{{ $package->general_companyProfile==1?'chk-green':'clode-red' }}"></div></td>
                    @endforeach
                  </tr>
                  <tr>
                    <td>Company Dashboard</td>
					@foreach ($packages as $package)
        
                   <td><div class="{{ $package->general_companyDashboard==1?'chk-green':'clode-red' }}"></td>
                    @endforeach
                  </tr>
                  <tr>
                    <td>Profile Insights</td>
					@foreach ($packages as $package)
        
                    <td><div class="{{ $package->general_profileInsights==1?'chk-green':'clode-red' }}"></div></td>
                    @endforeach
                  </tr>
                  <tr>
                    <td>Access to Media Options</td>
					@foreach ($packages as $package)
                  <td><div class="{{ $package->general_accesToMedia==1?'chk-green':'clode-red' }}"></div></td>
                    @endforeach
                  </tr>
                  <tr class="gray-sel">
                    <td><b>Marketplace</b></td>
					@foreach ($packages as $package)
        
                    <td></td>
                    @endforeach
                  </tr>
                  <tr>
                    <td>Search & find products</td>
					@foreach ($packages as $package)
        
                    <td><div class="{{ $package->market_Search==1?'chk-green':'clode-red' }}"></div></td>
                    @endforeach
                  </tr>
                  <tr>
                    <td class="set-height">Product Uploads</td>
					@foreach ($packages as $package)
					<td class="marketUploads">
                        <?php 
                        if($package->market_uploads!='') 
                        echo $package->market_uploads;
                        else
                        { ?>
                            <div class="unlimited un-top"><b>Unlimited</b><br />+ service FMCG Land <a href=""><i class="fa fa-info-circle" aria-hidden="true" data-bs-toggle="tooltip" data-bs-placement="top" title="Service"></i></a> </div>
                        <?php } ?>
                    </td>
                     @endforeach
                  </tr>
                  <tr>
                    <td>View Product Requests</td>
					@foreach ($packages as $package)
        
                   <td><div class="{{ $package->market_productRequests==1?'chk-green':'clode-red' }}"></div></td>
                    @endforeach
                  </tr>
                  <tr>
                    <td class="set-height2">Create Product Requests </td>
					@foreach ($packages as $package)
        
                    <td class="createProductRequest">
                        <?php 
                        if($package->market_createProductRequests!=1) 
                        echo '<div class="clode-red"></div>';
                        else
                        echo '<div class="unlimited"><b>Unlimited</b> </div>'; ?>
                    </td>
                    @endforeach
                  </tr>
                  <tr class="gray-sel">
                    <td><b>Network Feed</b></td>
					@foreach ($packages as $package)
        
                   <td></td>
                    @endforeach
                  </tr>
                  <tr>
                    <td>Expand your Network</td>
					@foreach ($packages as $package)
                        <td><div class="{{ $package->network_expand==1?'chk-green':'clode-red' }}"></div></td>
                    @endforeach
                  </tr>
                  <tr>
                    <td>Chat with Network</td>
					@foreach ($packages as $package)
                     <td><div class="{{ $package->network_chat==1?'chk-green':'clode-red' }}"></div></td>
                    @endforeach
                  </tr>
                 <tr  class="blank-tr">
                     <td></td>
                     	@foreach ($packages as $package )
                     <td></td>
                    @endforeach
                 </tr>
                  <tr class="btn-tr">
                      <td></td>
                    @foreach ($packages as $package)
                     <td>
                        
                        
                         @if($stripe_status=="Enabled" && $package->package_basic_price>0)
                            <a class="default-btn table-btn" href="{{route('subscription.checkout', ['pkg_id'=>$package->id, 'accounts_id'=>''])}}" ><span>subscribe now</span></a>
                        @else
                            <form method="post" action="{{route('cart.submit')}}" name="direct_submit">
                                   
                            @csrf
                            <input type="hidden" name="extended" value="no">
                            <input type="hidden" name="accounts_id" value="">
                            <input type="hidden" name="package_id" value="{{$package->id}}">
                            <button class="table-btn "><span>subscribe now</span></button>
                            </form>
                            
                           @endif 
                        <!--
                         @if($stripe_status=="Enabled" && $package->package_basic_price>0)
                            <form action="{{route('package.details')}}" method="get"  name="direct_submit1">
                        @else
                            <form method="post" action="{{route('cart.submit')}}" name="direct_submit">
                        @endif           
                            @csrf
                            <input type="hidden" name="extended" value="no">
                            <input type="hidden" name="accounts_id" value="">
                            <input type="hidden" name="package_id" value="{{$package->id}}">
                            <button class="table-btn "><span>subscribe now</span></button>
                            </form>
                            
                            -->
                          
                    </td>
                    @endforeach
                  </tr>
                </tbody>
              </table>
        </td>
       
    </tr>
</table>
 <?php } else echo '<h3 class="no-mor-pkg" style="color:#ff0000;"> No more packe to upgrade!!</h3>'; ?>                
                  
                      
                     
                </div>
                <div class="tabl-btm-txt">
                  <div class="row">
                    <div class="col-lg-6 col-12">
                      <p>Welcome to our B2B social business platform! Join a community built for connections and opportunities. Whether you're a startup, wholesaler, distributor, or corporation, our platform streamlines networking. Discover like-minded professionals, explore collaborations, and access resources effortlessly. Let's shape the future of business together!</p>
                    </div>
                    <div class="col-lg-1 col-12">
                      <div class="assistance"><img src="assets/images/Assistance svg.png" /></div>
                    </div>
                    <div class="col-lg-5 col-12">
                      <h5>Need any assistance?</h5>
                      <p>Don't hesitate to contact us now. Our Support Team will be more than willing to help you out setting up your perfect personal plan.<br/>
                        <a href="">Contact us now!</a></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!--pack-end-->

  
  
  <div class="container" hidden="true">
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
               <!-- <form action="{{route('package.details1')}}" method="get">-->
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
 //var $fmcg = $.noConflict();
     $fmcg('#pills-profile-tab').addClass('active');
     $fmcg('#pills-profile').addClass('active show');
     $fmcg('#pills-home-tab').removeClass('active');
     $fmcg('#pills-home').removeClass('active show');
   
  </script>
  <?php
  $extended_class = " show active"; 
}
?>
@endsection

@section('footer_script')
<script>
  $fmcg(".account_li").click(function(){ 
      var accounts_id = $fmcg(this).attr('id');
      var package_id =$fmcg(this).attr('data-id');
      $fmcg('.accounts_id'+package_id).val(accounts_id);
      $fmcg(this).parents('.drpbuttonli').find('.p1').text($fmcg(this).find('a').text()); 
      $fmcg(this).parents('.drpbuttonli').find('.p1').val($fmcg(this).find('a').text()); 
   });
</script>
@endsection