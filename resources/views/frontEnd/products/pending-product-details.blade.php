@extends('layouts.template')
@section('title', 'Product Details')
@section('content')
<link href="{{ asset('/css/image-zoom.css')}}" rel="stylesheet">

<section class="product-details-area ptb-54">

  <div class="container">
  <?php
  $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];        
  $usertype = '';
  if(Auth::guard('user')->check()) 
  {
  $logged_id = Auth::guard('user')->user()->id; 
  $usertype = Auth::guard('user')->user()->usertype; 
  }
  else   $logged_id = ''; ?>
  <div class="breadcrumb"> <a href="{{ route('home') }}">Home</a>
  @if(!empty($cats))
    @foreach($cats as $cat)
         &nbsp;<i class='fa fa-angle-double-right' style="margin-top: 3px;"></i> &nbsp;<a href="{{route('Product.Listing',['search_key'=>$cat->slug])}}">{{ $cat->name}} </a> 
    @endforeach
    @endif
    &nbsp;<i class='fa fa-angle-double-right' style="margin-top: 3px;"></i> &nbsp;{{ $product->name ?? ""}} 
    
  </div>
  
    <div class="row align-items-center">
      <div class="product-view-one">
        <div class="modal-content p-0">
          <div class="row align-items-center">
            <div class="col-lg-5 col-12"> 
              <ul id="glasscase" class="gc-start">
            
            <?php $cnt=count($product->SellerProductImageTemp) ; ?>
              @if($cnt>0)
              @if(!empty($product->SellerProductImageTemp))
						  @foreach ($product->SellerProductImageTemp as $product_image)
						  @if(filter_var($product_image->image_path, FILTER_VALIDATE_URL) === FALSE)
						  @php $validB64 = preg_match("/data:([a-zA-Z0-9]+\/[a-zA-Z0-9-.+]+).base64,.*/", $product_image->image_path); @endphp
                          @if($validB64)
						  <li><img src="{{$product_image->image_path}}" /></li>
						  @else
						  <li><img src="{{URL::asset('/uploads/productImages/'.'/'.$product_image->image_path)}}" /></li>
						  @endif
						  @else
    						 @php $handle = @fopen($product_image->image_path, 'r');@endphp
                                @if($handle)
                                     
                                     
                                      <li><img src="{{$product_image->image_path}}" /></li>
                                     
                              @endif
                          @endif
                @endforeach
                @endif  
                @else    
              <li><img src="{{URL::asset('/uploads/defaultImages/no_image.jpg')}}" /></li>
            @endif
              </ul>
            </div>
            <div class="col-lg-7 col-12">
              <div class="product-content ml-15">
                <h3> {{ $product->name ?? ""}} </h3>
                <div class="product-review">






</div>


              
	  <div class="price"> 
	  <span class="new-price"> 
	  @if($is_active_subscriber  && $usertype!="guest")
          
                    @if(strtolower(trim($product->price_on_request)) != strtolower('Price on request'))
                    
                   <?php
                   
                   if($currency_info!='') $currency = $currency_info['symbol'];
                    else
                    { 
                        if(!empty($product->currency))
                             $currency = $product->currency;
                        else   
                              $currency = '$';  
                    }
                    
                    echo $currency; 
                   $prod_price = $product->product_price;
                   $no_of_dec =0;
                   if (strpos($prod_price, ".") !== false) {
                         $v_fileFloatb = explode(".", $prod_price);
                         $no_of_dec = strlen($v_fileFloatb[1]);   
                   }

                   
                    if($no_of_dec==1)
                   echo '&nbsp;'.rtrim(rtrim(number_format($product->product_price,3), '0'), '.').'0';
                   else 
                   echo '&nbsp;'.rtrim(rtrim(number_format($product->product_price,3), '0'), '.');
                   ?> 
                   
                   @if(strtolower(trim($product->price_negotiable)) == strtolower('Negotiable')) (Price Negotiable) @endif
           
           
           @else
                   @if($logged_id != $seller->id ) 
                       <a href="javascript:void(0)" class="" onclick="EnableChatRequest({{$seller->id }})" >Price on Request</a> 
                   @else 
                      <div  class="new-price1">Price on Request</div>
                   @endif
          
           @endif
           </span>
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
          @endif
	  
	  
	  </div>
               
          
         
               
                
                 <ul class="product-info">
				@if(!empty($seller->BuyerCompany->company_name))
                  <li> <span>Company:<b>:</b></span>
                  {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $seller->BuyerCompany->company_name  ?? 'N/A' }}
                  </li>
				  @endif
				  @if(!empty($product->minimal_order))
                  <li> <span>Minimal Order Quantity:<b>:</b></span>
                  {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->minimal_order  ?? 'N/A' }}
                  </li>
                 @endif


@if(!empty($product->User->country_id))
<?php  $user_country_id = $product->User->country_id; 	?>
<li style="disply:block; clear:both;"> <span>Country<b>:</b></span>
@if((!$is_active_subscriber ||$usertype=="guest")) **** @else
                  
     <i class="fa fa-map-marker" aria-hidden="true"></i>
             	@foreach ($countries as $country)
               @if(($country->id == $user_country_id))
                               
              {{ $country->name }} 
              @endif 
              @endforeach              
                  
@endif                  
@endif


                  @if(!empty( $product->brands))
                  <li><span> Brand/Producer<b>:</b></span> 
                  {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->brands ?? 'N/A' }}
                  </li>
				  @endif
                </ul>
               
                


              </div>
            </div>
          </div>
        </div>
      </div>
    
	
	
	
	

      <div class="col-lg-12 col-md-12">
        <div id="reviews" class="tab products-details-tab">
          <div class="row">
            <div class="col-lg-12 col-md-12">
              <ul class="tabs">
                @if($is_active_subscriber)
                <li> Company information </li>
                @endif
                <li> Description </li>
                <li> Product Information </li>
                
               
                
              </ul>
            </div>
            <div class="col-lg-12 col-md-12">
              <div class="tab_content">
                  @if($is_active_subscriber)
                <div class="tabs_item">
                  <div class="products-details-tab-content">
                                        
                     <div class="pro-table">
                     <table class="table table-bordered">
                      <tbody>
                        <tr>
						@if(!empty($user_1->BuyerCompany->company_name))
                          <td>Company name</td>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $user_1->BuyerCompany->company_name?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty($seller->name))
                        <tr>
                          <td>Name </td>
                          <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $seller->name?? 'N/A' }}  </td>
                        </tr>
						@endif
						@if(!empty($seller->surname))
                        <tr>
                          <td>Surname</td>
                          <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $seller->surname?? 'N/A' }} </td>
                        </tr>
						@endif
						@if(!empty($user_1->address)||!empty($user_1->BuyerCompany->company_street))
                        <tr>
                          <td>Address </td>
						
                          <td>  <?php 
						  if((!$is_active_subscriber ||$usertype=="guest")) echo '****';
						else {  
							if(!empty($user_1->address)) echo $user->address."<br>";
							if(!empty($user_1->BuyerCompany->company_street)) echo $user_1->BuyerCompany->company_street."<br>";
							//if(!empty($user_1->BuyerCompany->company_location)) echo $user_1->BuyerCompany->company_location."<br>";
							//if(!empty($user_1->BuyerCompany->company_zip)) echo $user_1->BuyerCompany->company_zip ;
						}  ?></td>
                        </tr>
						@endif
						@if(!empty($user_1->BuyerCompany->company_zip))
                        <tr>
                          <td>Zip Code </td>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' :  $user_1->BuyerCompany->company_zip?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty($user_1->BuyerCompany->company_location))
                        <tr>
                          <td>Place </td>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' :  $user_1->BuyerCompany->company_location?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty($seller->Country->name))
                        <tr>
                          <td>Country </td>
                          <td> {{(!$is_active_subscriber ||$usertype=="guest") ? '****' : $seller->Country->name?? 'N/A' }}  </td>
                        </tr>
						@endif
						@if(!empty($seller->position))
                        <tr>
                          <td>Position </td>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $seller->position?? 'N/A' }} </td>
                        </tr>
						@endif
						@if(!empty($user_1->BuyerCompany->languages_speak))
                        <tr>
                          <td>Languages  </td>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $user_1->BuyerCompany->languages_speak?? 'N/A' }} </td>
                        </tr>
                        @endif
						@if(!empty( $user_1->BuyerCompany->SellerOpeningTime))
                         <tr>
						 <td>Opening hours </td>
                          <td>
                            <?php if((!$is_active_subscriber ||$usertype=="guest"))  echo "****";
                              else { ?>
                              
                              
						  @if($is_active_subscriber)
                       <?php   $today  = Carbon::now();
							   $Today  = Carbon::parse($today)->format('l');
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
                             } ?>
                       @endforeach
                       @else
                      <span>N/A</span>
                     @endif                     
				
					@endif 
<?php } ?>
	  </td>
                        </tr>
						@endif 
						@if(!empty( $seller->phone))
                        <tr>
                          <td>Phone number </td>
                          <td> {{  (!$is_active_subscriber ||$usertype=="guest") ? '****' : $seller->phone?? 'N/A'}}</td>
                        </tr>
                        @endif
                        
                      </tbody>
                    </table>
                  </div></div>
                </div>
                @endif
                <div class="tabs_item">
                  <div class="products-details-tab-content">
                   
                    <p><?php if(!$is_active_subscriber ||$usertype=="guest") echo'****';
					else
					echo $product->product_description ?$product->product_description : ""; ?></p>
                  </div>
                </div>
                <div class="tabs_item">
                  <div class="products-details-tab-content">
              
                    
                    
                    
                    <div class="products-details-tab-content">
                       <div class="pro-table">
                    <table class="table table-bordered">
                
                      <tbody>
     <!--                 @if(!empty($product->available_countries) || !empty($product->country_ids))    -->
     <!--                     <tr>-->
                           
     <!--                     <td>Product avaialable in:</td>-->
     <!--                     <td> @if(!$is_active_subscriber ||$usertype=="guest") '****'-->
					<!--		   @else                      -->
                          
					<!--				@if(!empty($product->country_ids))-->
					<!--					<i class="fa fa-map-marker" aria-hidden="true"></i> -->
					<!--						<?php $i = 1;  ?>-->
					<!--							@foreach ($countries as $country)-->
					<!--								<?php $selectedCountries = explode(",",$product->country_ids); $len = count($selectedCountries); ?>-->
					<!--									@if(in_array($country->id, $selectedCountries))-->
					<!--									{{ $country->name }}-->
					<!--								<?php if($i < $len){echo ',';} $i++;?>-->
					<!--									@endif -->
			  
					<!--							@endforeach-->

									
					<!--				@else-->
     <!--                                   {{ $product->available_countries ?? 'N/A' }}-->
					<!--				@endif -->
					<!--		@endif                       -->
     <!--                     </td>-->
     <!--                   </tr>-->
					<!--@endif        -->
                               @if(!empty( $product->label_language))
                         <tr>
                          <td>Etiket / Label Language:</td>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' :$product->label_language ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty( $product->SKU))
                         <tr>
                          <td>SKU:</td>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' :$product->SKU ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty( $product->EAN_GTIN))
                         <tr>
                          <td>EAN/GTIN:</td>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' :$product->EAN_GTIN ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty( $product->batch))
                         <tr>
                          <td>Batch#:</td>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' :$product->batch ?? 'N/A' }}</td>
                        </tr>
						@endif
                        @if(!empty($product->product_condition))						
                        <tr>
                          <td>Condition:</td>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' :$product->product_condition ?? 'N/A' }}</td>
                        </tr>
						@endif
						<!--@if(!empty($product->product_color))-->
      <!--                  <tr>-->
      <!--                    <td>Color: </td>-->
      <!--                    <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->product_color ?? 'N/A' }}</td>-->
      <!--                  </tr>-->
						<!--@endif-->
						<!--@if(!empty($product->product_size))-->
      <!--                  <tr>-->
      <!--                    <td>Size:</td>-->
      <!--                    <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->product_size ?? 'N/A' }}</td>-->
      <!--                  </tr>-->
						<!--@endif-->
						@if(!empty($product->product_weight||$product->product_size))
                        <tr>
                          <td>Product Weight/Size:</td>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->product_weight ?? $product->product_size ??  'N/A' }} </td>
                        </tr>
						@endif
						<!--@if(!empty($product->product_dimension))-->
      <!--                  <tr>-->
      <!--                    <td>Dimensions: </td>-->
      <!--                    <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->product_dimension ?? 'N/A' }}</td>-->
      <!--                  </tr>-->
						<!--@endif-->
						@if(!empty($product->location))
                        <tr>
                          <td>Stock Location: </td>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->location ?? 'N/A' }}</td>
                        </tr>
						@endif
						<!--@if(!empty($product->feature ))-->
      <!--                  <tr>-->
      <!--                    <td>Features: </td>-->
      <!--                    <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->feature ?? 'N/A' }}</td>-->
      <!--                  </tr>-->
						<!--@endif-->
						<!--@if(!empty($product->brix))-->
      <!--                  <tr>-->
      <!--                    <td>Brix(%): </td>-->
      <!--                    <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->brix ?? 'N/A' }}</td>-->
      <!--                  </tr>-->
						<!--@endif-->
						<!--@if(!empty($product->packaging))-->
      <!--                  <tr>-->
      <!--                    <td>Packaging: </td>-->
      <!--                    <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->packaging ?? 'N/A' }}</td>-->
      <!--                  </tr>-->
						<!--@endif-->
						@if(!empty( $product->place_of_origin))
                        <tr>
                          <td>Place of Origin: </td>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->place_of_origin ?? 'N/A' }}</td>
                        </tr>
						@endif
						
						
                        
						@if(!empty( $product->pcs_box))
                        <tr>
                          <td>Pcs/Box: </td>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->pcs_box ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty( $product->pcs_pallet))
                        <tr>
                          <td>Pcs/Pallet: </td>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->pcs_pallet ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty( $product->box_pallet))
                        <tr>
                          <td>Box/Pallet: </td>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->box_pallet ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty( $product->leadtime))
                        <tr>
                          <td>Lead Time: </td>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->leadtime ?? 'N/A' }}</td>
                        </tr>
						@endif
						<!--@if(!empty($product->model_number))-->
      <!--                  <tr>-->
      <!--                    <td>  Model Number</td>-->
      <!--                    <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->model_number ?? 'N/A' }}</td>-->
      <!--                  </tr>-->
						<!--@endif-->
						<!--@if(!empty($product->primary_ingredients))-->
      <!--                  <tr>-->
      <!--                    <td>Primary ingredients: </td>-->
      <!--                    <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->primary_ingredients ?? 'N/A' }}</td>-->
      <!--                  </tr>-->
						<!--@endif-->
						<!--@if(!empty($product->additives))-->
      <!--                  <tr>-->
      <!--                    <td>Additives: </td>-->
      <!--                    <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->additives ?? 'N/A' }}</td>-->
      <!--                  </tr>-->
						<!--@endif-->
						<!--@if(!empty( $product->flavor))-->
      <!--                  <tr>-->
      <!--                    <td>Flavor: </td>-->
      <!--                    <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->flavor ?? 'N/A' }}</td>-->
      <!--                  </tr>-->
						<!--@endif-->
						<!--@if(!empty( $product->certification))-->
      <!--                  <tr>-->
      <!--                    <td>Certification: </td>-->
      <!--                    <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->certification ?? 'N/A' }}</td>-->
      <!--                  </tr>-->
						<!--@endif-->
						<!--@if(!empty($product->volume))-->
      <!--                  <tr>-->
      <!--                    <td>Volume: </td>-->
      <!--                    <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->volume ?? 'N/A' }}</td>-->
      <!--                  </tr>-->
						<!--@endif-->
						<!--@if(!empty($product->BBD))-->
      <!--                  <tr>-->
      <!--                    <td>BBD: </td>-->
      <!--                    <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->BBD ?? 'N/A' }}</td>-->
      <!--                  </tr>-->
						<!--@endif-->
						@if(!empty( $product->product_expiry||$product->BBD))
                        <tr>
                          <td>BBD/Expiry Date: </td>
                          <?php $exp_date =  date("d-m-Y", strtotime($product->product_expiry ?? $product->BBD ?? '')); ?>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $exp_date ?? 'N/A' }}</td>
                        </tr>
                        @endif
                      
                        
                      </tbody>
                    </table>
                  </div></div>
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
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
</section>

<script src="{{asset('/js/image-zoom.js')}}" type="text/javascript"></script>

<script type="text/javascript">

$fmcg(document).ready( function () {
            $fmcg('#glasscase').glassCase({ 'thumbsPosition': 'bottom', 'widthDisplay' : 560});
});
var wish_count = <?=$view_composer_wishCount?>;
        function addToWishlist(id = null){ 
            
                if(id != null){
                    $fmcg.ajax({
                        type:'GET',
                        url:'{{ route("addToWishlist") }}',
                        data:{id: id, '_token':'{{csrf_token()}}'},
                        success:function(response){
                         $fmcg('#active_wish').show();
             $fmcg('#inactive_wish').hide();
             
             wish_count = wish_count+1;
            $fmcg('.wish-count').html(wish_count);
            var res = $fmcg('.wish-count').html();
                         if( res == 0)
              {  $fmcg('.wishlist_count').hide(); }
               else {  $fmcg('.wishlist_count').show();  }
             
              $fmcg('#outer_wish').hide();
                           
                            if(response.result){
                              
                            } else {
                                alert(response.message);
                            }
                        }
                    });
                } else {
                    alert('Wishlist Failed');
                }
            
        }


  
  function RemoveWishlist(id = null){  
            
                if(id != null){
                    $fmcg.ajax({
                        type:'GET',
                        url:'{{ route("RemoveWishlist") }}',
                        data:{id: id, '_token':'{{csrf_token()}}'},
                        success:function(response){
                         // location.reload();
             $fmcg('#inactive_wish').show();
             $fmcg('#active_wish').hide();
              $fmcg('#outer_wish').hide();
              wish_count = wish_count-1;
            $fmcg('.wish-count').html(wish_count);
            var res = $fmcg('.wish-count').html();
                         if( res == 0)
              {  $fmcg('.wishlist_count').hide(); }
            else {  $fmcg('.wishlist_count').show();  }
             
             
                            if(response.result){
                            } else {
                                alert(response.message); 
                            }
                        }
                    });
                } else {
                    alert('Wishlist Failed');
                }
            
        }




function EnableChatRequest(seller_id){
        $fmcg(".message-input").css("display", "block");
        $fmcg("#frame").css("display", "block");
        $fmcg(".content").css("display", "block");
        $fmcg("#frame").css("width", "95%");
       
                 $fmcg("#selected_id").val(seller_id);
                 loadMessage();
                  $fmcg("#frame").show();
                  senter = seller_id;
          
        
}
    </script>





      

@endsection

