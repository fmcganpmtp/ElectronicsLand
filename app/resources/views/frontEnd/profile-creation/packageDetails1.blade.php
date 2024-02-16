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


<form method="post" action="{{route('cart.submit')}}" name="direct_submit">
                     @csrf
                     <input type="hidden" name="package_id" value="{{ $package->id ?? '' }}">
                     <input type="hidden" name="accounts_id" value="{{ $accounts->id ?? '' }}">	
                     <input type="hidden" name="order_total" value="<?= $grand_total ?>">
               
                  </form>
<script>
    window.onload = function(){
  document.forms['direct_submit'].submit();
}
</script>
