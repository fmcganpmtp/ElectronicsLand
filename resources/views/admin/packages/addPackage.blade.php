@extends('admin.master')
@section('title', 'Create Subscription Package')
@section('breadcrumb') Subscription Package Creation @endsection
@section('content')




 
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>SUBSCRIPTION PACKAGES CREATION</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header"> </div>
                <div class="card-body">
                  
                  
                  <form method="post" action ="{{route('save.admin.package')}}">
                    @csrf
					
                  <div class="row">
                  	<div class="col-lg-6 col-12">
					
                    	 <div class="form-group">
                      <label>Name :</label>
                      <input type="text" name="name" value="{{old('name')}}"   class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" required>
                      @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                       @endif
                    </div>
                    
                  <!--
                        <div class="form-group">
                        <div class="no-over">
                          <label>Package type :</label>
                        </div>
                      <label>
				    <input type="radio" class="option-input radio" name="subscription_type" checked  onClick="hide_extented_details();" value="Standard"/>
				   Standard
				  </label>
				  <label>
				    <input type="radio" class="option-input radio" name="subscription_type"  onClick="show_extented_details();" value="Extended"/>
				        Extended
				  </label>
				  @if ($errors->has('subcription_type'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('subcription_type') }}</strong>
                                    </span>
                       @endif 
                   </div>                
                  
  <div id="extended_details" style="display:none;">
    <div id="fields_extent">      
                          <div class="form-group">
                                <label for="name" >No. of accounts :</label> 
							<input type="number"  name="no_of_accounts[]" class="form-control clsrequired" >
                            </div>

						<div class="form-group">
                                <label for="name" >Cost per account : </label> 
								<input type="number" step="0.01" name="cost_per_account[]" class="form-control clsrequired" >
                            </div>

                          <div class="form-group">
                                <label for="name" >Offer price : </label> 
								<input type="number" step="0.01" name="per_account_offer_price[]" class="form-control clsrequired">
                            </div>	

    </div>



                    <span class="btn btn-success pkg_add round-button"><i class="fa fa-plus-circle" aria-hidden="true" style="color:#fff;"></i></span>
							
</div>	
-->							
							
<!--
	
                      <div class="form-group three-radio">
                        <div class="no-over">
                          <label>User type :</label>
                        </div>
                        <label class="control control--checkbox">
                        Seller
                        <input type="radio" name="user_type" value="Seller" checked/>
                        <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">
                        Buyer
                        <input type="radio" name="user_type" value="Buyer" />
                        <div class="control__indicator"></div>
                        </label>
                       
						@if ($errors->has('user_type'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('user_type') }}</strong>
                                    </span>
                       @endif 
                      </div>
-->
                   <div class="form-group">
                            <div class="no-over">
                                <label>Validity:</label>
                            </div>
                         <label> <input type="radio" class="option-input radio" name="package_validity" checked  onClick="show1();" value="3 months"/> 3 Months </label>
                         <label> <input type="radio" class="option-input radio" name="package_validity"  onClick="show2();" value="6 months"/> 6 Months </label>
                         <label> <input type="radio" class="option-input radio" name="package_validity"  onClick="show2();" value="One year"/> One Year </label>
                            @if ($errors->has('package_validity'))
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('package_validity') }}</strong>
                                </span>
                             @endif 
                        </div>
                   <div class="form-group">
                        <div class="no-over">
                          <label>Price :</label>
                        </div>
                     <input type="number" step="0.01" name="package_basic_price" value="{{old('package_basic_price')}}"    class="form-control {{ $errors->has('package_basic_price') ? ' is-invalid' : '' }}" required>
				  @if ($errors->has('package_basic_price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('package_basic_price') }}</strong>
                                    </span>
                       @endif 
                   </div>       
                   <div class="form-group">
                        <div class="no-over">
                          <label>Offer price :</label>
                        </div>
                     <input type="number" step="0.01" name="package_offer_price" value="{{old('package_offer_price')}}"    class="form-control {{ $errors->has('package_offer_price') ? ' is-invalid' : '' }}" >
				  @if ($errors->has('package_offer_price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('package_offer_price') }}</strong>
                                    </span>
                       @endif 
                   </div>
                   
                   <div class="form-group">
                      <label style="display:block;clear:both;">Description :</label>
                      <textarea  name="package_description"    class="form-control {{ $errors->has('package_description') ? ' is-invalid' : '' }}" required >{{old('package_description')}}</textarea>
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
                        <input type="radio" name="general_companyProfile" value="0" />
                        <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">
                        Yes
                        <input type="radio" name="general_companyProfile" value="1" checked/>
                        <div class="control__indicator"></div>
                        </label> 
                    </div>

                    <div class="form-group three-radio">
                        <div class="no-over">
                          <label>Access to Company Dashboard :</label>
                        </div>
                        <label class="control control--checkbox">
                        No
                        <input type="radio" name="general_companyDashboard" value="0" />
                        <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">
                        Yes
                        <input type="radio" name="general_companyDashboard" value="1" checked/>
                        <div class="control__indicator"></div>
                        </label> 
                    </div>

                    <div class="form-group three-radio">
                        <div class="no-over">
                          <label>Access to Profile Insights	:</label>
                        </div>
                        <label class="control control--checkbox">
                        No
                        <input type="radio" name="general_profileInsights" value="0" />
                        <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">
                        Yes
                        <input type="radio" name="general_profileInsights" value="1" checked/>
                        <div class="control__indicator"></div>
                        </label> 
                    </div>

                    <div class="form-group three-radio">
                        <div class="no-over">
                          <label>Access to Media Options :</label>
                        </div>
                        <label class="control control--checkbox">
                        No
                        <input type="radio" name="general_accesToMedia" value="0" />
                        <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">
                        Yes
                        <input type="radio" name="general_accesToMedia" value="1" checked/>
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
                        <input type="radio" name="market_Search" value="0" />
                        <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">
                        Yes
                        <input type="radio" name="market_Search" value="1" checked/>
                        <div class="control__indicator"></div>
                        </label> 
                    </div>

                    <div class="form-group three-radio">
                        <div class="no-over">
                          <label>Product Uploads (<i>Keep blank for unlimited</i>):</label>
                        </div>
                        <input type="number" name="market_uploads" value="" class="form-control " placeholder="Keep blank for unlimited"> 
                    </div>

                    <div class="form-group three-radio">
                        <div class="no-over">
                          <label>Access to View Product Requests :</label>
                        </div>
                        <label class="control control--checkbox">
                        No
                        <input type="radio" name="market_productRequests" value="0"  checked/>
                        <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">
                        Yes
                        <input type="radio" name="market_productRequests" value="1"/>
                        <div class="control__indicator"></div>
                        </label> 
                    </div>

                    <div class="form-group three-radio">
                        <div class="no-over">
                          <label>Access to Create Product Requests :</label>
                        </div>
                        <label class="control control--checkbox">
                        No
                        <input type="radio" name="market_createProductRequests" value="0" checked />
                        <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">
                        Unlimited
                        <input type="radio" name="market_createProductRequests" value="1"/>
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
                        <input type="radio" name="network_expand" value="0" checked/>
                        <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">
                        Yes
                        <input type="radio" name="network_expand" value="1" />
                        <div class="control__indicator"></div>
                        </label> 
                    </div>

                    <div class="form-group three-radio">
                        <div class="no-over">
                          <label>Allow Chat with Network :</label>
                        </div>
                        <label class="control control--checkbox">
                        No
                        <input type="radio" name="network_chat" value="0" checked/>
                        <div class="control__indicator"></div>
                        </label>
                        <label class="control control--checkbox">
                        Yes
                        <input type="radio" name="network_chat" value="1" />
                        <div class="control__indicator"></div>
                        </label> 
                    </div>
				  
                    <div class="form-group">
                            <label for="name">Display Order :</label>

                            <div>
                                <input id="display_order" type="number" class="form-control" name="display_order" value="" >

                                                            </div>
                        </div>		 
                    

            <button type="submit" class="bl-btn">Submit</button>
</form>



            

</div></div>
                         



</div>
                  </div>
				   </div>
				    </div>
					 </div>
					  </div> </div> 

                <div class="row clone-group" id="clone" style="display: none;">
                        <div class="row clone-group">
                            
                            
                                <div class="form-group" style="position:relative;">
                                    <label for="name" >No. of accounts :</label> 
                                    <input type="number" name="no_of_accounts[]" class="form-control clsrequired" >
                                    <span style="position:absolute;top: 23px;right: 13px;" class="red_round remove-input-field"><i class="fa fa-minus-circle" aria-hidden="true"></i></span>
                                </div>
                            
                                
                                <div class="form-group">
                                    <label for="name" >Cost per account : </label> <input type="number" step="0.01" name="cost_per_account[]" class="form-control clsrequired" >
                                </div>
                            
                                <div class="form-group">
                                    <label for="name" >Offer price :  </label> <input type="number" step="0.01" name="per_account_offer_price[]" class="form-control clsrequired" >
                                </div>
                            
                        </div>
                    </div>
<!--					  </div>
<script type="text/javascript">
    var i = 0;
    $("#dynamic-ar").click(function () {
        ++i;
        $("#dynamicAddRemove").append('<div class="outer_included"><input type="text" name="addMoreInputFields[' + i +
            '][feature]" placeholder="Enter Included feature" class="form-control" /><a class="removeclass remove-input-field"><i class="fa fa-minus-circle" aria-hidden="true"></i></a></div>'
            );
    });
    $(document).on('click', '.remove-input-field', function () {
        $(this).parents('.outer_included').remove();
    });
</script>
<script type="text/javascript">
    var i = 0;
    $("#dynamic-excluded").click(function () {
        ++i;
        $("#dynamicAddRemoveExluded").append('<div class="outer">  <input type="text" name="addMoreInputFieldsExcluded[' + i +
            '][feature]" placeholder="Enter excluded  feature" class="form-control" /><a class="removeclass remove-input-field"><i class="fa fa-minus-circle" aria-hidden="true"></i></a></div>'
            );
    });
    $(document).on('click', '.remove-input-field', function () {
        $(this).parents('.outer').remove();
    });



function hide_extented_details(){
  document.getElementById('extended_details').style.display ='none';
  $(".clsrequired").prop('required',false);  
}
function show_extented_details(){
  document.getElementById('extended_details').style.display = 'block';   
  $(".clsrequired").prop('required',true);  
}

</script>

<script type="text/javascript">
        $(document).ready(function() {
            $(".pkg_add").click(function() { 
                var html = $("#clone").html();
                $("#fields_extent").append(html);
            });

            $("body").on("click", ".remove-input-field", function() {
                $(this).parents(".clone-group").remove();
            });
        });
    </script>
    -->
@endsection