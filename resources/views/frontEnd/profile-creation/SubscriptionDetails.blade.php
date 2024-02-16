@extends('layouts.template')
@section('title', 'Subscription Details')
@section('content')
<?php
$today = Carbon::now(); 
$today = Carbon::createFromFormat('Y-m-d H:s:i', $today);
 ?>
<sectiion class="seller-page">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="pr-bottom">
          <div class="card sbcrb-dt">
            <div class="row">
              <div class="col-lg-126 col-12 srt-bt">
              <h3>Subscription Details 
              @if(count($subscriptions)==0)
                <a class="subscr default-btn sr-rt" href="{{route('package.details')}}" style="    box-shadow: -1px 3px #888a1a;"><i class="fa fa-telegram" aria-hidden="true"></i>Subscribe Packages </a></h3>
              @endif
              </div>
            </div>
            <div class="tableC subcrib-dt subcrib-dt8">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th scope="col">Package</th>
                    <th scope="col">Amount</th>
					<th scope="col">Purchased Amount</th>
                    <th scope="col">Exp. Date</th>
                     <th scope="col">Auto renew</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                @if(count($subscriptions)==0)
                <tr><td colspan="6" style="color:red;">No Data Found !!</td></tr>
                @endif
                @if(!empty($subscriptions))
                @foreach( $subscriptions as $subscription)
            <!-- corresponding packages of order using relation -->
                <?php  $package = $subscription->Package;  ?>
                  <tr>
                    <td><a href="{{ route('PackgeInvoice', ['package_id'=>$subscription->package_id,'subscription_id'=>$subscription->id]) }}"><h5><i class="fa fa-dot-circle-o" aria-hidden="true"></i>{{$subscription->Package->name ?? ''}} </h5></a></td>
                    <td>&euro; {{$package->package_basic_price ?? ''}} </td>
					 <td>&euro; {{$subscription->order_total ?? ''}}</td>
                    <td>
                      <?php 
                      $purchsed_date = date('d - M - Y', strtotime($subscription->created_at)); 
                      $package_validity = $package->package_validity;
                      $Enddate =  $subscription->expairy_date;
                      $Enddate =  Carbon::createFromFormat('Y-m-d', $Enddate);
                      ?>
                    {{$subscription->expairy_date ?? ''}}
					<?php
					$dates_remining = $Enddate->diffInDays($today);
					//dateDiffInDays($Enddate); //calling function
					if($Enddate->isToday()) { ?> <h6 class="rd">Expiring Today</h6><?php } 
                          elseif($Enddate->isPast()) { ?> <h6 class="rd">EXPIRED!</h6><?php } 
					      elseif($subscription->status == "Active" && $dates_remining<=15) {
					       ?>
                      <h6 class="rd">package will expire in {{ $dates_remining }} days</h6>
                    <?php } elseif($subscription->status == "Active" && $dates_remining>15){?>
                     <h6 class="rd" style="color:green;">ACTIVE</h6>
                    <?php }?>
                    </td>
                      <td>
  <div class="switch-field"> 
   <!-- <input disabled type="radio" id="radio-{{$subscription->id}}" name="switch-{{$subscription->id}}" value="1" {{ ($subscription->auto_renewal=="1")? "checked" : "" }} />-->
     <input disabled type="radio" id="radio-{{$subscription->id}}" name="switch-{{$subscription->id}}" value="1"  />
    <label for="radio-{{$subscription->id}}">On</label>
    <input disabled type="radio" id="radio-{{$subscription->package_id}}" name="switch-{{$subscription->id}}" value="0"  checked />
    <label for="radio-{{$subscription->package_id}}">Off</label>
  </div>
  </td>
                    <td>
                    <?php if($Enddate->isPast()&& !$Enddate->isToday()) { ?> <span class="expr2"><i class="fa fa-times" aria-hidden="true"></i>Expired</span>
                    <?php }
                    elseif($subscription->status=='Suspended') { ?> <span class="expr2"><i class="fa fa-times" aria-hidden="true"></i>Suspended</span>
                    <?php } else { ?> 
                    <span class="act2"><i class="fa fa-check-square-o" aria-hidden="true"></i>{{ $subscription->status ?? '' }}</span>
                     <?php } ?>
                    </td>
                    <td> 
           @if($subscription->status == "Active" && ($Enddate->isFuture() || $Enddate->isToday()))
			<?php 
			//$dates_remining = $Enddate->diffInDays($today);
			if($package->subscription_type=='Extended'){ 
		        $current_acc_id = $subscription->OrderDetail->accounts_id;
                $pac_acc=[];
		        if(!empty($package->PackageAccount)){
		        foreach($package->PackageAccount as $pac_account)
		        { 
			      array_push($pac_acc,$pac_account->id);
		        } } ?>
					 <a href="{{ route('upgrade.package',['package_basicPrice'=>$package->package_basic_price,'order_type'=>'Upgrade','old_pkg_id'=>$package->id])}} " class="btn btn-outline-success">Upgrade</a>
                     @if($package->status=='deleted')
                        <a  onclick="sweetAlert('This Package is no longer available.', '', 'error');"  href="#"  class="btn btn-outline-info">Renew </a>
				     @elseif(!in_array($current_acc_id, $pac_acc) && $dates_remining<=15)
                        <a  onclick="sweetAlert('This Package -- Account details changed. So not able to renew.', '', 'error');"  href="#"  class="btn btn-outline-info">Renew </a>
                     @elseif($dates_remining<=15)
                        
	                    <a  href="{{ route('renew.package',['package_id'=>$package->id,'accounts_id'=>$subscription->OrderDetail->accounts_id ,'order_type'=>'Renew','old_pkg_id'=>$package->id])}}"  class="btn btn-outline-info">Renew </a>
                     @endif
					   <?php } 
					   else { 
              if($highestPackage->id!=$package->id){
                ?>	

					    <a href="{{ route('upgrade.package',['package_basicPrice'=>$package->package_basic_price,'order_type'=>'Upgrade','old_pkg_id'=>$package->id])}} " class="btn btn-outline-success">Upgrade</a>
              <?php } ?>     
              @if($package->status=='deleted')
                        <a  onclick="sweetAlert('This Package is no longer available.', '', 'error');"  href="#"  class="btn btn-outline-info">Renew </a>
                    @elseif($dates_remining<=15)
                       @if($package->package_basic_price>0)
	                    <!--<a  href="{{ route('renew.package',['package_id'=>$package->id,'accounts_id'=>$subscription->OrderDetail->accounts_id ,'order_type'=>'Renew','old_pkg_id'=>$package->id])}}"  class="btn btn-outline-info">Renew </a>
	                    -->
	                    <a  href="{{route('subscription.checkout', ['pkg_id'=>$package->id, 'accounts_id'=>'','order_type'=>'Renew','old_pkg_id'=>$package->id])}}" class="btn btn-outline-info"><span>Renew</span></a>
	                    
	                    @else
	                    <form method="post" action="{{route('cart.submit')}}" name="direct_submit">
                                  
                            @csrf
                            <input type="hidden" name="package_id" value="{{$package->id}}">
                            <input type="hidden" name="order_type" value="Renew">
                            <input type="hidden" name="old_pkg_id" value="{{$package->id}}">
                            <button class="btn btn-outline-info renw-nw">Renew</button>
                            </form>
	                    @endif
                    @endif
					   <?php } ?>
					@endif
                    @if($subscription->status == "Active" && $Enddate->isPast() && !$Enddate->isToday() )       
                        <a class="btn btn-outline-success" href="{{route('expired.package',['order_type'=>'Expired','old_pkg_id'=>$package->id])}}" ><i class="fa fa-telegram" aria-hidden="true"></i>Subscribe Packages </a>     
                    @endif     
                     </td>
                  </tr>
                  @endforeach
                @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
</sectiion>
@endsection