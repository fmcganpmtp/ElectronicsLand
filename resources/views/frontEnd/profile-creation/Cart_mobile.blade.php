@extends('layouts.template_mobile')
@section('title', 'Cart')
@section('content')
<sectiion class="seller-page-new no-bg chk-out-page-mb">

  <div class="container">
    <div class="row"> </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="pr-bottom">
          <div class="card  tp-30 wow fadeInUp"> 
           <?php
           $offer_price = $package->package_offer_price ?? "";
           $basic_price = $package->package_basic_price;
           if ($offer_price != "" || $offer_price > 0) {
               $basic_price = $package->package_offer_price;
           }
           if ($package->subscription_type == "Extended") {
               $account_offer_price = $accounts->per_account_offer_price ?? "";
               $account_basic_price = $accounts->cost_per_account ?? "";
               if ($account_offer_price != "" || $account_offer_price > 0) {
                   $account_basic_price = $account_offer_price;
               } 
               $no_of_accounts = $accounts->no_of_accounts;
               $profile_cost = $account_basic_price * $accounts->no_of_accounts;
               $grand_total = $basic_price + $profile_cost;
               if (!empty(Session::get("last_oreder_total"))) {
                   $grand_total = $basic_price + $profile_cost - Session::get("last_oreder_total");
               }
           } else {
               $no_of_accounts = $account_basic_price = $profile_cost = "";
               $grand_total = $basic_price;
               if (!empty(Session::get("last_oreder_total"))) {
                   $grand_total =$basic_price - Session::get("last_oreder_total");
               }
           }
           $pkg_id = $act_id = "";
           if (!empty($package->id)) {
               $pkg_id = $package->id;
           }
           if (!empty($accounts->id)) {
               $act_id = $accounts->id;
           }
           Session::put("package_id", $pkg_id);
           Session::put("accounts_id", $act_id);
           Session::put("order_total", $grand_total);
           if ($grand_total < 0) {
               $grand_total = 0;
           }
             if ($grand_total > 0) { ?>
             <form action="{{route('session')}}" method="POST">
             <?php } else { ?>
            <form method="post" action="{{route('cart.submit')}}">
                <?php } ?>
            <div class="row">
            @csrf
            <input type="hidden" name="package_id" value="{{ $package->id ?? '' }}">
            <input type="hidden" name="accounts_id" value="{{ $accounts->id ?? '' }}">
              <div class="col-lg-8 col-12">
                <div class="tableC">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Package name</th>
                        <th>Cost</th>
                        <!--<th>Additional Profiles</th>
                        <th>Profile Cost</th>-->
                        <th>Validity</th>
                        <th>Auto renewal </th>
						@if(!empty(Session::get('last_oreder_total')))
							<th>Prev Discount </th>
						@endif
                        <th>Grand Total </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>{{ $package->name   }}</td>
                          <td>&euro; {{ $basic_price }}</td>
                         <!-- <td>{{ $no_of_accounts }}
                         @if($package->subscription_type=="Extended") (CHF{{ $account_basic_price   }} / Profile) @endif</td>
                        <td>@if($package->subscription_type=="Extended") CHF{{ $profile_cost }} @endif</td>
                        -->
                        <td>{{ $package->package_validity   }}</td>
                        <td>
						<?php
       if ($view_composer_profile_menu_visible_criteria['flag2'] == true) { ?>
	<div class="switch-field"> 
    <input disabled type="radio" id="radio-{{$package->id}}" name="auto_renewal" value="1" />
   <!-- <input disabled type="radio" id="radio-{{$package->id}}" name="auto_renewal" value="1" {{ ($auto_renewal=="1")? "checked" : "" }} />-->
    <label for="radio-{{$package->id}}">On</label>
    <input disabled type="radio" id="radio-{{$package->package_id}}" name="auto_renewal" value="0"  checked />
    <label for="radio-{{$package->package_id}}">Off</label>
    </div>
		<?php } else { ?>
	<div class="switch-field">
    <input disabled type="radio" id="radio-{{$package->id}}" name="auto_renewal" value="1" />
    <label for="radio-{{$package->id}}">On</label>
    <input disabled type="radio" id="radio-{{$package->id}}2" name="auto_renewal" value="0" checked/>
    <label for="radio-{{$package->id}}2">Off</label>
	</div>
		     <?php }
      ?>
 </td>
 @if(!empty(Session::get('last_oreder_total')))
 <td>&euro; {{ Session::get('last_oreder_total') ?? '' }} </td>
 @endif
						<td>&euro; {{ $grand_total }} </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="col-lg-4 col-12">
                <div class="paynment-right">
                  <h3>TOTAL</h3>
                  <ul>
                    <li class="sub-ttl-mb">Sub total<b>:</b></li>
                    <li><span>&euro; {{ $grand_total }}</span></li>
                  </ul>
                  <div class="form-group form-check">
                    <input required type="checkbox" value="yes" class="form-check-input" id="exampleCheck1" name="privacy_check">
                    <label class="form-check-label" for="exampleCheck1">I agree to the <a href="{{ route('privacy.policy') }}" target="_blank">privacy policy and terms of use</a></label>
                  </div>
                  <div class="form-group form-check">
                    <input required type="checkbox" value="yes" class="form-check-input" id="exampleCheck2" name="terms_check">
                    <label class="form-check-label" for="exampleCheck1"> I agree the <a href="{{ route('terms.condition') }}" target="_blank">terms and conditions</a></label>
                  </div>
                  <input type="hidden" value="<?= $grand_total ?>" name="amount" >
                  <input type="hidden" value="<?= $package->name ?>" name="item" >
                    <button type="submit" id="checkout-live-button"   class="btn btn-success ck-out">Pay now</button>
                  <h5>we accept</h5>
                  <div class="cards-C"><img src="{{ asset('/images/payment-icon2.png')}}"></div>
                </div>
              </div>
            </div>
			<input type="hidden" name="order_total" value="<?= $grand_total ?>">
      </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</sectiion>
@endsection