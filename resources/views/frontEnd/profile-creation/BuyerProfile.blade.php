@extends('layouts.template')
@section('title', 'Buyer Profile')
@section('content')

<?php
function dateDiffInDays($Enddate) { 

//$to = Carbon::createFromFormat('Y-m-d H:s:i', $Enddate);
$to = $Enddate;
$today = Carbon::now();
$today = Carbon::createFromFormat('Y-m-d H:s:i', $today);

$diff_in_days = $to->diffInDays($today);

return $diff_in_days;

} ?>

<sectiion class="seller-page no-bg">
<?php $usertype = Auth::guard('user')->user()->usertype; ?>
  <div class="container buyer-dtl55">
    <div class="row">
      <div class="col-lg-12">
        <div class="pr-bottom">
          <div class="card">
            <div class="slr-details buyer-dtl">
              <div class="row">
                <div class="col-lg-3 col-12">
                  
                    <?php
                    if(!empty(Auth::guard('user')->user()->profile_pic)) $img_path = asset('/uploads/userImages/').'/'.Auth::guard('user')->user()->profile_pic;
                    else  $img_path = asset('uploads/defaultImages/default_avatar.png');
                    if($user->varification_status=="varified") $active_class= " active-border";
             else $active_class= "";
                    ?>
                    <div class="pr-logo {{$active_class}}">
                  <img src="{{$img_path}}">
</div>
                    
                  
                  <h2>{{$user->name }} {{$user->surname}}</h2>
                </div>
                <div class="col-lg-9 col-12">
                  <h3>Personal Information  </h3>
                  <div class="row">
                    <div class="col-lg-6 col-12">
                      <ul class="buyer-lft-dl">
                        <li><i class="fa fa-envelope-o" aria-hidden="true"></i> {{ Auth::guard('user')->user()->email ?? '' }}</li>
                        <li><i class="fa fa-phone" aria-hidden="true"></i> {{ Auth::guard('user')->user()->phone ?? '' }}</li>
                      </ul>
                    </div>
                    <div class="col-lg-6 col-12">
                      <ul>
                        <li>{{ Auth::guard('user')->user()->name ?? '' }},</li>
                        <li> {{ Auth::guard('user')->user()->address ?? '' }}</li>
                        
                      </ul>
                    </div>
                  </div>
                  
                   @if(empty($subscriptions) ) <h5 class="rd" style="color:#ff0000;">No Active Subscriptions Packages</h5>
                     @else(!empty($subscriptions))
                     <div class="acc-dtl">
                    <h4>
                    Account created : <?php  $purchsed_date = date('d - M - Y', strtotime($subscriptions->created_at));?> {{$purchsed_date ?? ''}} </h4>
                    <h5> {{$order->name ?? ''}} </h5>
                    
                    
                   
                   
                    <?php 
                      $purchsed_date = date('d - M - Y', strtotime($subscriptions->created_at)); 
                      $package = $subscriptions->Package;
                      $package_validity = $package->package_validity;
                      $Enddate = $subscriptions->expairy_date;
                      $Enddate =  Carbon::createFromFormat('Y-m-d', $Enddate);
                      $dates_remining = dateDiffInDays($Enddate);
                      //$updatedDateFormat =  Carbon::createFromFormat('Y-m-d', $Enddate)->format('m-d-Y');
                   
                      ?>
                   
                    
                   
                    
                    
                    <h5  style="color:#ff0000;">Expire in {{$dates_remining}} days </span></h5>
                  
                    </div>
                    @endif
                  
                  <div class="row">
                    <div class="col-lg-5"> 
					@if(Auth::guard('user')->user()->seller_type!='Co-Seller')
					@if($usertype=="guest")
					<a href="{{route('package.listing')}}" class="button-99 top10p nav-link">
                      <div class="bottom"></div>
                      <div class="top">
                        <div class="label">Subscribe Packages </div>
                        <div class="button-border button-border-left"></div>
                        <div class="button-border button-border-top"></div>
                        <div class="button-border button-border-right"></div>
                        <div class="button-border button-border-bottom"></div>
                      </div>
                      </a> 
					  @else
						  <a href="{{route('package.listing', ['pkg_type'=>'seller'])}}" class="button-99 top10p nav-link">
                      <div class="bottom"></div>
                      <div class="top">
                        <div class="label">Become a Seller</div>
                        <div class="button-border button-border-left"></div>
                        <div class="button-border button-border-top"></div>
                        <div class="button-border button-border-right"></div>
                        <div class="button-border button-border-bottom"></div>
                      </div>
                      </a> 
					  @endif
					  @endif
					  </div>
                      <div class="col-lg-2"></div>
                      <div class="col-lg-5"> 
                      @if($email_status=="No")
                      <div class="col-lg-12">
                             @if($usertype!="guest" && Auth::guard('user')->user()->seller_type!='Co-Seller') <form method="post" action="{{route('sendBuyerApprovalMail')}}" > @csrf
                          <button style="padding: 10px 36px;" type="submit" class="btn-right "><i class="fa fa-check-circle-o" aria-hidden="true"></i>Verify email address</button>
                          </form>
						  @endif
                       </div>
				             @endif
                    </div>


 @if(Session::has('message')) <div style="margin-top:25px;" class="alert alert-success">{{Session::get('message') }}</div> @endif
           


                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              @if($network==true)
           
              <div class="col-lg-12"> <a href="javascript:void(0)" onclick="addtonetwork({{$user->id}})" class="default-btn ctr mr-pro">Add To Networks</a> </div>
           
            @endif
           
            </div>
          </div>
          
		  
		  @if($usertype!="guest" && Auth::guard('user')->user()->seller_type!='Co-Seller')
          <div class="card comp-details">
            <div class="row">
            
            <div class="col-lg-12"><h3>Company information </h3></div>
            </div>
            
            <div class="subscrip-detail-top subscrip-detail-right ">

                         
                      <label><b>Street</b> : <span class="ad-rol-name">{{$user->BuyerCompany->company_street ?? '' }}</span></label>
                       <label><b>Zip Code </b> : <span class="ad-rol-name">{{$user->BuyerCompany->company_zip ?? '' }}</span></label>
                       <label><b>Location </b>: <span class="ad-rol-name">{{$user->BuyerCompany->company_location ?? '' }}</span></label>
                       @empty($user->SellerOpeningTime)  
                       <label><b>Opening hours</b> :
                        <table class="table table-bordered"> 
                          <tr><th>Day</th><th>Opening Time</th><th>Closing Time</th></tr>
                         
                       @foreach ($user->SellerOpeningTime as $Spening)
        <tr>

            <td>{!! $Spening->day !!}</td>
            @if($Spening->closed=="Yes")
            <td colspan="2">Closed</td>
            @else
            <td>@if($Spening->opening_time!=":"){!! $Spening->opening_time !!}@endif</td>
            <td>@if($Spening->closing_time!=":"){!! $Spening->closing_time	 !!}@endif</td>
            @endif
        </tr>
    @endforeach
</table>
                      </label>
                      @endempty  
                       @php 
           $company_image =  $user->BuyerCompany->company_image ?? '';
           if(($company_image!=""))
          $cmp_img = asset('uploads/BuyerCompany/').'/'.$user->BuyerCompany->company_image;
          else $cmp_img = asset('uploads/defaultImages/seller.jpg');
          @endphp
                         <label class="cmp-logoC"><b>Company Logo</b>: <span class="ad-rol-name up-im"><div class="up-pic"><img src="{{$cmp_img}}"></div></span></label>
                       
                       
                        <label><b> Preferred payments for its products </b> : <span class="ad-rol-name">{{$user->BuyerCompany->accepted_payments ?? '' }}</span></label>
                       
                       
                      
                       
                       
                        <label><b>The languages that he/she speaks for communication purposes


</b>: <span class="ad-rol-name">{{$user->BuyerCompany->languages_speak ?? '' }}</span></label>

<label><b>Address


</b>: <span class="ad-rol-name">{{$user->BuyerCompany->company_name ?? '' }}<br>{{$user->BuyerCompany->company_street ?? '' }}<br></span></label>
                         
                         
                    </div>
                  
                  
                  
                  
                  
                  
              
            </div>
          
          @endif
          
          
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="Mymodal" tabindex="-1" aria-labelledby="MymodalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="MymodalLabel">Request for Account Delete</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="fnclosepopup1()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
     <div class="form-group">
          <label>Reason:</label>
          <br>
          <textarea id="reason_delete" class="form-control" required></textarea> 
     </div>
          <button  type="submit"  class="bl-btn mar-10" onclick="fnsubmitdelete()">SUBMIT</button>
        
      </div>
      <div class="modal-footer">
       
      </div>
    </div>
  </div>
</div>
</sectiion>
<script>
//var $fmcg = $.noConflict();
  function fnclosepopup1(){
    $fmcg("#Mymodal").modal('hide');
}

function fnsubmitdelete(){



  $fmcg.ajax({
            url: "{{ url('profile_account_delete') }}",
               type: "get",
               data:{ 
                   _token:'{{ csrf_token() }}',
                   user_id: '<?=$user->id?>',
                   reason:$fmcg("#reason_delete").val() ,
               },
               async:true,
               cache: false,
               dataType: 'json',
               success: function(){ 
                $fmcg("#Mymodal").modal('hide');
                 swal("Request for removing your account successfully placed. Our admin will remove it very soon", "", "success");
                 //location.reload();
             } ,
             error: function(XMLHttpRequest, textStatus, errorThrown) { 
               swal(errorThrown, "", "error");
             }  
   
           })  ;

}
function deleteProfileaccount(userid){

  $fmcg("#reason_delete").val('');
  swal({
    title:"Do you really want to delete? " ,
    text: "",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      
         $fmcg('#Mymodal').modal('show');

    } 
    else {
          
        }
    });  


}
</script>

@endsection
