@extends('admin.master')
@section('title', 'Edit Package')
@section('breadcrumb') Subscription Package Edit @endsection
@section('content')

<style>

 .removeclass {
    font-size: 25px;
    float: right;
    position: absolute;
    top: -1px;
 } 
 .control--checkbox {
    width: 23%;
}
 </style>


<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Subscription Package Edit </h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 col-12">
              <div class="card ">
<!--                <div class="card-header">Profile Information</div>
-->                <div class="card-body">
                  
                    <form method="POST" action="{{ route('update.package') }}" aria-label="{{ __('Register') }}" >
                    
                        @csrf
                     <input type="hidden" name="package_id" value="{{ $package->id }}">
                        <div class="form-group">
                            <label for="name" >{{ __('Name :') }}</label>

                            <div >
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $package->name }}" required >

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
						<!--
                        <div class="form-group">
                            <label style="display:block; clear:both;" for="name" >{{ __('Subscription Type :') }}</label>

							<label class="control control--checkbox"> Standard
                                <input  @if(($package->subscription_type=="Standard")) checked @endif type="radio" class="option-input radio" name="subscription_type" checked  onClick="show1();" value="Standard"> 
                                <div class="control__indicator"></div>
                        </label>
							
							<label class="control control--checkbox"> Extended
                                <input  @if(($package->subscription_type=="Extended")) checked @endif type="radio" class="option-input radio" name="subscription_type"  onClick="show2();" value="Extended">  
                                <div class="control__indicator"></div>
                       </label>
						</div>
						
						
                        <div class="row" id="fields_extent" style="display:block; clear:both;" >	
						<div id="myElem"></div>
                     @foreach ($package->PackageAccount as $package_account)
                        <div class="row clone-group" id="outer_account_{{$package_account->id}}">
                           
                                <div class="form-group" style="position:relative;">
                                    <label>No. of accounts :</label> 
									<input type="text" name="no_of_accounts[]" value="{{$package_account->no_of_accounts}}" class="form-control clsrequired" required>
									<span class="removeclass remove-input-field delete_ext" onclick="removeAccount({{$package_account->id}})" style=" top: 24px;right: 17px;"><i class="fa fa-minus-circle" aria-hidden="true"></i></span>
									
                                </div>
                            
                                <div class="form-group">
                                    <label>Cost per account : </label> 
									<input type="text" name="cost_per_account[]" value="{{$package_account->cost_per_account}}" class="form-control clsrequired" required>
                                </div>
                            
                                <div class="form-group">
                                    <label>Offer price : </label> <input type="text" name="per_account_offer_price[]" value="{{$package_account->per_account_offer_price}}" class="form-control clsrequired" required>
                                </div>
                            
                            <input type="hidden" name="package_account_id[]" value="{{$package_account->id}}" />
                        </div>
                    @endforeach
                    
                        
                    
                </div>
					<div class="input-group-btn basicaccount" id="divnewpackage">
                                    <button class="btn btn-success" type="button"><i class="fa fa-plus" style="color:#fff;"></i></button>
                                </div>	
						
					
						
					

					
						
						<br>
						<div class="form-group" style="display:block;clear:both;">
                            <label style="display:block; clear:both;" for="name" >{{ __('User type :') }}</label>

                            <div >
							<label class="control control--checkbox"> Seller
                                <input  @if(($package->user_type=="Seller")) checked @endif type="radio" class="option-input radio" name="user_type" checked   value="Seller"> 
                                <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">
                        Buyer
								<input  @if(($package->user_type=="Buyer")) checked @endif type="radio" class="option-input radio" name="user_type"   value="Buyer">
                                <div class="control__indicator"></div>
                        </label>
                       
																
                                @if ($errors->has('user_type'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('user_type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						-->
						<div class="form-group">
                            <label style="display:block; clear:both;" for="name" >{{ __('Validity:') }}</label>

                            <div >
							<label class="control control--checkbox"> 3 Months
                                <input  @if(($package->package_validity=="3 months")) checked @endif type="radio" class="option-input radio" name="package_validity" checked   value="3 months"> 
                                <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">
                        6 Months
								<input  @if(($package->package_validity=="6 months")) checked @endif type="radio" class="option-input radio" name="package_validity"   value="6 months">
                                <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">
                        One Year
								<input  @if(($package->package_validity=="One year")) checked @endif type="radio" class="option-input radio" name="package_validity"   value="One year"> 								
                                <div class="control__indicator"></div>
                        </label>
							   @if ($errors->has('package_validity'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('package_validity') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						<div class="form-group" style="display:block;clear:both;">
                            <label style="display:block; clear:both;" for="name" >{{ __('Price :') }}</label>

                            <div >
                                <input type="number" step="0.01" name="package_basic_price"  value="{{ $package->package_basic_price }}"   class="form-control {{ $errors->has('package_basic_price') ? ' is-invalid' : '' }}" >

                                @if ($errors->has('package_basic_price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('package_basic_price') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
						<div class="form-group">
                            <label style="display:block; clear:both;" for="name" >{{ __('Offer price :') }}</label>

                            <div >
                                <input type="number" step="0.01" name="package_offer_price"  value="{{ $package->package_offer_price }}"   class="form-control {{ $errors->has('package_offer_price') ? ' is-invalid' : '' }}" >

                                @if ($errors->has('package_offer_price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('package_offer_price') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                      <label style="display:block; clear:both;" for="name" >Description :</label>
                      <textarea  name="package_description"    class="form-control {{ $errors->has('package_description') ? ' is-invalid' : '' }}" >{{ $package->package_description }}</textarea>
                      @if ($errors->has('package_description'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('package_description') }}</strong>
                                    </span>
                       @endif
                    </div>


                        <div class="form-group">
                      <div class="no-over">
                        <p>Select access permissions for this package </p>
                        <h2>General:</h2>
                      </div>                    
                    </div> 
                   
                   <div class="form-group three-radio">
                        <div class="no-over">
                          <label>Access to Company Profile :</label>
                        </div>
                        <label class="control control--checkbox">
                        No
                        <input type="radio" name="general_companyProfile" value="0" @if(($package->general_companyProfile==0)) checked @endif />
                        <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">
                        Yes
                        <input type="radio" name="general_companyProfile" value="1" @if(($package->general_companyProfile==1)) checked @endif />
                        <div class="control__indicator"></div>
                        </label> 
                    </div>

                    <div class="form-group three-radio">
                        <div class="no-over">
                          <label>Access to Company Dashboard :</label>
                        </div>
                        <label class="control control--checkbox">
                        No
                        <input type="radio" name="general_companyDashboard" value="0" @if(($package->general_companyDashboard==0)) checked @endif/>
                        <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">
                        Yes
                        <input type="radio" name="general_companyDashboard" value="1" @if(($package->general_companyDashboard==1)) checked @endif/>
                        <div class="control__indicator"></div>
                        </label> 
                    </div>

                    <div class="form-group three-radio">
                        <div class="no-over">
                          <label>Access to Profile Insights	:</label>
                        </div>
                        <label class="control control--checkbox">
                        No
                        <input type="radio" name="general_profileInsights" value="0" @if(($package->general_profileInsights==0)) checked @endif/>
                        <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">
                        Yes
                        <input type="radio" name="general_profileInsights" value="1" @if(($package->general_profileInsights==1)) checked @endif/>
                        <div class="control__indicator"></div>
                        </label> 
                    </div>

                    <div class="form-group three-radio">
                        <div class="no-over">
                          <label>Access to Media Options :</label>
                        </div>
                        <label class="control control--checkbox">
                        No
                        <input type="radio" name="general_accesToMedia" value="0" @if(($package->general_accesToMedia==1)) checked @endif/>
                        <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">
                        Yes
                        <input type="radio" name="general_accesToMedia" value="1" @if(($package->general_accesToMedia==1)) checked @endif/>
                        <div class="control__indicator"></div>
                        </label> 
                    </div>
                  				   
                    <div class="form-group">
                      <div class="no-over">
                        <h2>Marketplace:</h2>
                      </div>                    
                    </div> 
                   
                    <div class="form-group three-radio">
                        <div class="no-over">
                          <label>Access to Search & find products :</label>
                        </div>
                        <label class="control control--checkbox">
                        No
                        <input type="radio" name="market_Search" value="0" @if(($package->market_Search==0)) checked @endif/>
                        <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">
                        Yes
                        <input type="radio" name="market_Search" value="1" @if(($package->market_Search==1)) checked @endif/>
                        <div class="control__indicator"></div>
                        </label> 
                    </div>

                    <div class="form-group three-radio">
                        <div class="no-over">
                          <label>Product Uploads (<i>Keep blank for unlimited</i>):</label>
                        </div>
                        <input type="number" name="market_uploads" value="{{$package->market_uploads}}" class="form-control " placeholder="Keep blank for unlimited"> 
                    </div>

                    <div class="form-group three-radio">
                        <div class="no-over">
                          <label>Access to View Product Requests :</label>
                        </div>
                        <label class="control control--checkbox">
                        No
                        <input type="radio" name="market_productRequests" value="0"  @if(($package->market_productRequests==0)) checked @endif/>
                        <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">
                        Yes
                        <input type="radio" name="market_productRequests" value="1" @if(($package->market_productRequests==1)) checked @endif/>
                        <div class="control__indicator"></div>
                        </label> 
                    </div>

                    <div class="form-group three-radio">
                        <div class="no-over">
                          <label>Access to Create Product Requests :</label>
                        </div>
                        <label class="control control--checkbox">
                        No
                        <input type="radio" name="market_createProductRequests" value="0"  @if(($package->market_createProductRequests==0)) checked @endif />
                        <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">
                        Unlimited
                        <input type="radio" name="market_createProductRequests" value="1" @if(($package->market_createProductRequests==1)) checked @endif/>
                        <div class="control__indicator"></div>
                        </label> 
                    </div>

                    <div class="form-group">
                      <div class="no-over">
                        <h2>Network Feed:</h2>
                      </div>                    
                    </div> 
                   
                    <div class="form-group three-radio">
                        <div class="no-over">
                          <label>Allow Expand your Network :</label>
                        </div>
                        <label class="control control--checkbox">
                        No
                        <input type="radio" name="network_expand" value="0"  @if(($package->network_expand==0)) checked @endif/>
                        <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">
                        Yes
                        <input type="radio" name="network_expand" value="1"  @if(($package->network_expand==1)) checked @endif/>
                        <div class="control__indicator"></div>
                        </label> 
                    </div>

                    <div class="form-group three-radio">
                        <div class="no-over">
                          <label>Allow Chat with Network :</label>
                        </div>
                        <label class="control control--checkbox">
                        No
                        <input type="radio" name="network_chat" value="0"   @if(($package->network_chat==0)) checked @endif/>
                        <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">
                        Yes
                        <input type="radio" name="network_chat" value="1"   @if(($package->network_chat==1)) checked @endif/>
                        <div class="control__indicator"></div>
                        </label> 
                    </div>
						
						     
                       

                        <div class="form-group mb-0">
                            <div class="">
                                <button type="submit" class="bl-btn">
                                    {{ __('Update') }}
                                </button>
								</div>
                        </div>
                    </form>
                  
                  
                  
                  
                </div>

               
              </div>
            </div>
            
          </div>
        </div>
      </div>

    </div>

    <div class="row clone-group" id="clone" style="display: none;">
                    <div class="row clone-group" >
                        
                            
                        
                            <div class="form-group"  style="position:relative;">
                            
                            <label>No. of accounts :</label> <input type="text" name="no_of_accounts[]" value="" class="form-control clsrequired" >
                            <span class="removeclass remove-input-field delete_new" style=" top: 24px;right: 17px;"><i class="fa fa-minus-circle" aria-hidden="true"></i></span>
                            </div>
                        
                            <div class="form-group">
                                <label>Cost per account :  </label> 
                                <input type="text" name="cost_per_account[]" value="" class="form-control clsrequired" >
                                
                            </div>
                        
                            <div class="form-group">
                                <label>Offer price : </label> <input type="text" name="per_account_offer_price[]" value="" class="form-control clsrequired" >
                            </div>
                        
                    </div>
                </div>
                  
                  

  </div>

<script type="text/javascript">
function show1(){
    $(".basicaccount").css('display','none');
    $("#fields_extent").empty();
}
function show2(){
    $(".basicaccount").css('display','block');
    $("#fields_extent").empty();
   
}
    $(document).ready(function() {

        var package_type='<?php echo $package->subscription_type;?>';
        if(package_type=='Standard')
            $(".basicaccount").css('display','none');
        else
            $(".basicaccount").css('display','block');

       


        $("#divnewpackage").click(function(){ 
            
            $(".clsrequired").prop('required',true);  
            var html = $("#clone").html();

            $("#fields_extent").append(html);
        });
        $("body").on("click",".delete_new",function(){
            $(this).parents(".clone-group").remove();
        });

    });
    function removeAccount(cid){ 

       if(cid){
           var outerhtml =  $("#outer_account_"+cid).html();
           $("#outer_account_"+cid).html()
           $.ajax({

                type:"post",
                data: { id: cid, "_token": "{{ csrf_token() }}" },
                url:"{{ route('remove.packageAccount') }}", 
                success:function(res)
                {
                    if(res.ajax_status=='success'){
                        html = '';
                        $("#outer_account_"+cid).html(html);
                        $("#outer_account_"+cid).remove();
                        $("#myElem").html(res.message);
                        $("#myElem").show().delay(3000).fadeOut();

                    }else{
                        $("#outer_account_"+cid).html(outerhtml);
                        $("#myElem").html(res.message);
                        $("#myElem").show().delay(3000).fadeOut();
                    }
                }

            });
       }
   }
</script>


@endsection