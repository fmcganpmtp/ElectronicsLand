@extends('admin.master')
@section('title', 'View Seller Product')
@section('breadcrumb') View Seller Product @endsection
@section('content')
   



 
  <div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
          
        <h2>Product View</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">


         <div class="card-body ">
                 
           
           <div class="row align-items-center">
            <div class="col-lg-5">
            
  
    
            <ul id="glasscase" class="gc-start">
            <?php $cnt=count($product->SellerProductImage) ; ?>
              @if($cnt>0)
              @if(!empty($product->SellerProductImage))
						  @foreach ($product->SellerProductImage as $product_image)
              <li><img src="{{URL::asset('/uploads/productImages/'.'/'.$product_image->image_path)}}" /></li>
                @endforeach
                @endif  
                @else    
              <li><img src="{{URL::asset('/uploads/defaultImages/no_image.jpg')}}" /></li>
            @endif
              </ul>
              <script type="text/javascript">
                var $ = jQuery;
                (function($) {
        $(document).ready( function () {
            $('#glasscase').glassCase({ 'thumbsPosition': 'bottom', 'widthDisplay' : 560});
        });
      })(jQuery);
    </script>
            </div>
             <div class="col-lg-6">
              <div class="product-content ml-15">
                <h3> {{$product->name}}   </h3>
                <div class="product-review">
                  
            <?php 
             $stock_status ="<span style='color:#ff0000;'>Out Of Stock</span>";
             if($product->stock_count>0 || strtolower(trim($product->unlimited_stock)) == strtolower('Unlimited') )
              $stock_status ="<span style='color:green;'>In Stock</span>";
             ?>
			  <div class="price"> <span class="new-price">
			      
			     @if(strtolower(trim($product->price_on_request)) != strtolower('Price on request'))
                   <?php
                  $currency = '$';
                   if(!empty($product->Currency->symbol))
                   $currency = $product->Currency->symbol;
			       $prod_price = $product->product_price;
                    $no_of_dec =0;
                   if (strpos($prod_price, ".") !== false) {
                         $v_fileFloatb = explode(".", $prod_price);
                         $no_of_dec = strlen($v_fileFloatb[1]);   
                   }

                  
                   if($no_of_dec==1)
                   echo $currency.'&nbsp;'.rtrim(rtrim(number_format($product->product_price,3), '0'), '.').'0';
                   else 
                   echo $currency.'&nbsp;'.rtrim(rtrim(number_format($product->product_price,3), '0'), '.'); ?> 
                   @if(strtolower(trim($product->price_negotiable)) == strtolower('Negotiable')) (Price Negotiable) @endif
           
           
               @else
                 
                      <span  class="new-price1">Price on Request</span>
                @endif</span> 
			      
			      
			     <span class="in-stock">
			      <?php 
              $stock_status =""; 
              
              if($product->stock_count>0) 
              $stock_status ="<span style='color:#30B239;'>In Stock</span>";
              elseif(is_null($product->stock_count)) 
              $stock_status ="";
              elseif($product->stock_count ==0) 
              $stock_status ="<span style='color:#ff0000;'>Out Of Stock</span>";
              
				$stk_count= "";
		        if($product->stock_count>0)
					$stk_count= "(".$product->stock_count.")";
					
			  if(strtolower(trim($product->unlimited_stock)) == strtolower('Unlimited')) 
                echo "<span style='color:#30B239;'>Unlimited Stock</span>";
			  else
			    echo $stock_status.$stk_count;
			?>

 </span>
 
 
 </div>
               
                <ul class="product-info">
                  <li> <span>Company:<b>:</b></span>  {{  $seller->BuyerCompany->company_name  ?? 'N/A' }}</li>
                  <li> <span>Minimal Order Quantity<b>:</b></span> {{$product->minimal_order ?? "NA"}}</li>
                  <li> <span>Country<b>:</b></span>
                {{$seller->Country->name ?? ''  }}   
                </li>
                  <li><span> Brand/Producer<b>:</b></span> {{$product->Productbrand->name ?? "NA"}} </li>
                </ul>       
              </div>
            </div>
          </div>

                </div>
         
                
        <div class="row tb ">
        
        <div class="col-lg-12">
        	<ul class="nav nav-tabs" id="myTab" role="tablist">
	<li class="nav-item">
    <a class="nav-link active" id="company-tab" data-toggle="tab" href="#company" role="tab" aria-controls="home" aria-selected="true">Company Informations </a>
  </li>		
  <li class="nav-item">
    <a class="nav-link " id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Description </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Product Information</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="review-tab" data-toggle="tab" href="#review" role="tab" aria-controls="review" aria-selected="false">Reviews</a>
  </li>

</ul>
<div class="tab-content product-details-area " id="myTabContent">

<div class="tab-pane fade show active" id="company" role="tabpanel" aria-labelledby="company-tab">
  
  <div class="tabs_item">
                  <div class="products-details-tab-content">
                    
                    <div class="pro-table"><br>
                    <table class="table table-bordered" style="width:400px;">
                      <tbody>
                        <tr>
                          <td>Company name</td>
                          <td> {{ $user_1->BuyerCompany->company_name?? 'N/A' }}</td>
                        </tr>
                        <tr>
                          <td>Name </td>
                          <td>{{ $seller->name?? 'N/A' }}  </td>
                        </tr>
                        <tr>
                          <td>Surname</td>
                          <td>{{ $seller->surname?? 'N/A' }} </td>
                        </tr>
                        @if(!empty($user_1->address)||!empty($user_1->BuyerCompany->company_street))
                        <tr>
                          <td>Address </td>
                          <td><?php 
                          if(!empty($user_1->address)) echo $user_1->address."<br>";
						  if(!empty($user_1->BuyerCompany->company_street)) echo $user_1->BuyerCompany->company_street."<br>"; 
						  ?></td>
                        </tr>
                        @endif
                        <tr>
                          <td>Zip Code </td>
                          <td> {{  $user_1->BuyerCompany->company_zip?? 'N/A' }}</td>
                        </tr>
                        <tr>
                          <td>Place </td>
                          <td> {{ $user_1->BuyerCompany->company_location?? 'N/A' }}</td>
                        </tr>
                        <tr>
                          <td>Country </td>
                          <td> {{$seller->Country->name?? 'N/A' }}   </td>
                        </tr>
                        <tr>
                          <td>Position </td>
                          <td> {{ $seller->position?? 'N/A' }} </td>
                        </tr>
                        <tr>
                          <td>Languages  </td>
                          <td> {{  $user_1->BuyerCompany->languages_speak?? 'N/A' }} </td>
                        </tr>
                       <tr>
                          <td>Opening hours </td>
                          <td>
						  
                       <?php   $today  = Carbon::now(); 
                       $Today = Carbon::parse($today)->format('l'); 
                        ?>
                            @if(!empty( $user_1->BuyerCompany->SellerOpeningTime))
                      
                          @foreach ( $user_1->BuyerCompany->SellerOpeningTime as $Spening)
<?php
      if($Today==$Spening->day)
      { 
            if($Spening->closed=='Yes' || (is_null($Spening->opening_time)) ||  (is_null($Spening->closing_time)) )   
                      echo "<span style='color:red;'>Closed</span>";
                      else
                      { 
                                  $check = false;
                                  $opening_time =  $Spening->opening_time ;
                                  $closing_time =   $Spening->closing_time;
                                  $check = Carbon::now()->between($opening_time, $closing_time, true);
                      
                                  if($check)
                                      echo "<span style='color:green;'>Opened</span>";
                                  else
                                      echo "<span style='color:red;'>Closed</span>";
                               
                      }
      }
?>
      @endforeach
      @else
       <span>N/A</span>
      @endif                     




	  </td>
                        </tr>
                        <tr>
                          <td>Phone number </td>
                          <td> {{   $seller->phone?? 'N/A'}}</td>
                        </tr>
                        
                        
                      </tbody>
                    </table>
                    
                  </div>
                    
                    
                    
                    
                    </div>
                </div>
  
  </div>



  <div class="tab-pane fade show " id="home" role="tabpanel" aria-labelledby="home-tab">
  
  <div class="tabs_item">
                  <div class="products-details-tab-content">
                   
                    <p><?php echo $product->product_description ?$product->product_description : ""; ?></p>
                    </div>
                </div>
  
  </div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
  
  
  <div class="tab_content">
                
                <div class="tabs_item">
                  <div class="products-details-tab-content">
                    <ul class="additional-information">
         <!--               <li><span>Product avaialable in:</span>-->
                                               
                          
									<!--@if(!empty($product->available_countries))-->
									<!--	<i class="fa fa-map-marker" aria-hidden="true"></i> -->
									<!--		@php $i = 1;  @endphp-->
									<!--			@foreach ($countries as $country)-->
									<!--				@php $selectedCountries = explode(",",$product->available_countries); $len = count($selectedCountries); @endphp-->
														
									<!--					{{ $country }}-->
									<!--				@php if($i < $len){echo ',';} $i++;@endphp-->
													
			  
									<!--			@endforeach-->
         <!--                              @else N/A-->
									<!--@endif -->
							                      
         <!--                 </li>-->
                        <li><span>Etiket / Label Language:</span> {{ $product->label_language ?? 'N/A' }}</li>
                        <li><span>SKU:</span> {{$product->SKU ?? 'N/A' }}</li>
                        <li><span>EAN/GTIN:</span> {{$product->EAN_GTIN ?? 'N/A' }}</li>
                        <li><span>Batch#:</span> {{$product->batch ?? 'N/A' }}</li>
                        <li><span>Condition:</span> {{ $product->product_condition ?? 'N/A' }}</li>
                        <!--<li><span>Color:</span> {{  $product->product_color ?? 'N/A' }}</li>-->
                        <!--<li><span>Size:</span> {{  $product->product_size ?? 'N/A' }}</li>-->
                        <li><span>Product Weight/Size:</span> {{  $product->product_weight ??  $product->product_size ?? 'N/A' }} </li>
                        <!--<li><span>Dimensions:</span> {{  $product->product_dimension ?? 'N/A' }}</li>-->
                        <li><span>Stock Location:</span> {{  $product->location ?? 'N/A' }}</li>
                        <li><span>Pcs/Box:</span> {{  $product->pcs_box ?? 'N/A' }}</li>
                        <li><span>Pcs/Pallet:</span> {{  $product->pcs_pallet ?? 'N/A' }}</li>
                        <li><span>Box/Pallet:</span> {{  $product->box_pallet ?? 'N/A' }}</li>
                        <li><span>Lead Time:</span> {{  $product->leadtime ?? 'N/A' }}</li>
                        <!--<li><span>Features:</span> {{  $product->feature ?? 'N/A' }}</li>-->
                        <!--<li><span>Brix(%):</span> {{  $product->brix ?? 'N/A' }}</li>-->
                        <!--<li><span>Packaging:</span> {{  $product->packaging ?? 'N/A' }}</li>-->
                        <li><span>Place of Origin: </span> {{  $product->place_of_origin ?? 'N/A' }}</li>
                        <!--<li><span>Model Number:</span>{{  $product->model_number ?? 'N/A' }}</li>-->
                        <!--<li><span>Primary ingredients:</span> {{  $product->primary_ingredients ?? 'N/A' }}</li>-->
                        <!--<li><span>Additives:</span> {{  $product->additives ?? 'N/A' }}</li>-->
                        <!--<li><span>Flavor:</span> {{  $product->flavor ?? 'N/A' }}</li>-->
                        <!--<li><span>Certification:</span> {{  $product->certification ?? 'N/A' }}</li>-->
                        <!--<li><span>Volume: </span>{{  $product->volume ?? 'N/A' }}</li>-->
                        <!--<li><span>BBD: </span>{{  $product->BBD ?? 'N/A' }}</li>-->
                        <li><span>BBD/Expiry Date:</span> {{$product->product_expiry ?? $product->BBD ??  'N/A' }}</li>
                    </ul>
                  </div>
                </div>
                
              </div>
  </div>
  
   <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
  
  
  <div class="tab_content">
                
                <div class="tabs_item">
                  <div class="products-details-tab-content">
                                    <input type="hidden" id="start_from" value="0">
                <div class="tabs_item">
                  <div class="products-details-tab-content">
                    <div class="product-review-form">
                      <h3>Customer Reviews</h3>
                      <div class="review-comments" id="divreviews">
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
        </div>       

                
              </div>
            </div>
            
          </div>
        </div>

      </div>
    </div>
  </div>
  </div>
  
<!-- CoreUI and necessary plugins-->
<script src="{{asset('/admin1/vendors/@coreui/coreui/js/coreui.bundle.min.js')}}"></script>
<script src="{{asset('/admin1/vendors/simplebar/js/simplebar.min.js')}}"></script>
<!--newly added for file upload preview-->

<script src="{{asset('/admin1/js/image-zoom.js')}}" type="text/javascript"></script>
<script src="{{asset('/admin1/js/custom.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
<script src="{{asset('/admin1/js/bootstrap.min.js')}}"></script>
<link href="{{asset('/admin1/vendors/@coreui/icons/css/free.min.css')}}" rel="stylesheet">


<script type="text/javascript">



                var $ = jQuery;
                (function($) {
        $(document).ready( function ($) { 
           
            loadreviews();
        });
      })(jQuery);
    </script>

    <script>
        function loadreviews(){
  var product_id='<?php echo $productId;?>';
  
  
  $.ajax({
                            type : 'post',
                            url:"{{route('loadreview')}}",
                            data:{
                              _token:'{{ csrf_token() }}',
                              'product_id':product_id,
                              'start_from':$('#start_from').val(),
                              'per_page':3
                            },
                           

                            success:function(data)
                            {
                                  
                                   let dataItems = JSON.parse(data);
                                   console.log();
                                   var initial=0;
                                   var string ='';
                                   dataItems.productReviews.map((item) => {
									   var string1 ='';
                                    initial++;
                                string1+='<div class="review-item" >';
                                  
                                  var rating = parseInt(item.star_rating) ;
if(rating=='null')
rating=0;
   string1 +="<div class='rating'><span class='stars'>";
for ( var i = 1; i <= 5; i++ ) {
    if ( i <=rating) 
    string1+="<i class='fa fa-star'></i>"; 
 else 
 string1+="<i class='fa fa-star-o'></i>"; 
    
}
                                   string1+='</span></div>';
                                   string1+='<h3>';
                                   string1+=item.review_title+' </h3><span><strong>';
                                   string1+=item.name+' </strong> on <strong>';
                                   string1+=item.created_at+' </strong></span><p>';
                                   string1+=item.review+' </p></div>';
								   string=string1;
                                  $("#divreviews").append(string);
                                })
                              
                                   var skipcount=parseInt($("#start_from").val())+ initial ;
                                    if(dataItems.count!=skipcount)
                                      $("#divreviews").append('<br><div class="bl-btn " style="width:15%;margin:0 auto; text-align:center;" id="loadmore" onclick="loadmore()">Load More</div>');

                                   $("#start_from").val(skipcount);
         
                                   
                            }


                      });

}


function loadmore(){
  $("#loadmore").remove();
  loadreviews();
}
    </script>

@endsection