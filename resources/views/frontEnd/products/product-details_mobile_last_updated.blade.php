@extends('layouts.template_mobile')
@section('title', 'Product Details')
@section('content')
<link href="{{ asset('/css/image-zoom.css')}}" rel="stylesheet">

<section class="product-details-area ptb-54">

  <div class="container">
  <?php 
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
    @endif &nbsp;<i class='fa fa-angle-double-right' style="margin-top: 3px;"></i> &nbsp;{{ $product->name ?? ""}} 
  </div>
  
    <div class="row align-items-center">
      <div class="product-view-one">
        <div class="modal-content p-0">
          <div class="row align-items-center">
            <div class="col-lg-5 col-12"> 
              <ul id="glasscase" class="gc-start">
                <?php $cnt=count($product->SellerProductImage) ; ?>
              @if($cnt>0)
         @foreach ($product->SellerProductImage as $product_image)
              <li><img src="{{URL::asset('/uploads/productImages/'.'/'.$product_image->image_path)}}" /></li>
                @endforeach
                
                @else <li><img src="{{URL::asset('images/no-image.jpg')}}" /></li>
                @endif
                
                
              </ul>
           
            </div>
            <div class="col-lg-7 col-12">
              <div class="product-content ml-15">
                <h3> {{ $product->name ?? ""}} </h3>
                <div class="product-review">





@if($is_active_subscriber)
  
<?php $avgStar =''; 
$rating = 0 ; 
if($productReviewCounts->Starcount>0)
$avgStar = $productReviewCounts->TotalStar/$productReviewCounts->Starcount;
if(!empty($avgStar))
$rating = $avgStar ; 

echo "<span class='stars'>";
for ( $i = 1; $i <= 5; $i++ ) {
    if ( round( $rating - .25 ) >= $i ) {
        echo "<i class='fa fa-star'></i>"; //fas fa-star for v5
    } elseif ( round( $rating + .25 ) >= $i ) {
        echo "<i class='fa fa-star-half-o'></i>"; //fas fa-star-half-alt for v5
    } else {
        echo "<i class='fa fa-star-o'></i>"; //far fa-star for v5
    }
}
echo '</span>';

?>

@if(!empty($avgStar))  <a href="#reviews" class="rating-count">{{ $productReviewCounts->Starcount }} Reviews</a> 
@else <span style='color:#ff0000;'>No Reviews Yet!</span>
@endif
@endif
</div>
			 
          <div class="price"> <span class="new-price">
			
          @if($is_active_subscriber  && $usertype!="guest")
          
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
                   echo $currency.'&nbsp;'.rtrim(rtrim(number_format($product->product_price,3), '0'), '.');
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
	  
         
               
                  @if($seller->varification_status=="varified")<img style="width:160px;"  src="{{URL::asset('/uploads/defaultImages/approved_seller.png')}}" />@endif
                      
            @if(!empty($product->product_expiry))
                     <?php 
              $Enddate =  $product->product_expiry;
                      $Enddate =  Carbon::createFromFormat('Y-m-d', $Enddate); ?>
                      <?php if($Enddate->isPast()) { ?> <h6 class="rd" style="display:block;clear:both;">EXPIRED!</h6> <?php } ?>
                    @endif
                <ul class="product-info">
				@if(!empty($seller->BuyerCompany->company_name))
                  <li> <span>Company:<b>:</b></span>
                  {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $seller->BuyerCompany->company_name  ?? 'N/A' }}
                  </li>
				  @endif
				  @if(!empty($product->minimal_order))
                  <li> <span>Minimal order:<b>:</b></span>
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


                  @if(!empty( $product->Productbrand->name))
                  <li><span> Brand/Producer<b>:</b></span> 
                  {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->Productbrand->name ?? 'N/A' }}
                  </li>
				  @endif
                </ul>
                
                @if((!$is_active_subscriber ||$usertype=="guest") && ($logged_id != $seller->id ) )
                <div class="product-detail-btn" style="display: block;clear: both;padding-top: 20px;
"> <a class="subscr default-btn" href="{{route('package.listing')}}"><i class="fa fa-telegram" aria-hidden="true"></i>Subscribe to See Product Details </a> </div>
                @else
                
<div class="product-detail-btn shw-btn"  style="display: block;clear: both;padding-top: 20px;"> 
@if($logged_id != $seller->id )
<a href="javascript:void(0)" class="default-btn" onclick="EnableChatRequest({{$seller->id }})" ><i class="fa fa-envelope-o" aria-hidden="true"></i>Message </a>
@endif

@if($product->status=="active"&& ($logged_id != $seller->id ))
<a href="{{route('ViewSeller.profile',['profId'=>$seller->id])}}" class="default-btn"> <i class="fa fa-user" aria-hidden="true"></i>Seller profile</a> 

<span id="outer_wish">
@if($wish_listed=="Yes")
<a href="javascript:void(0)" onclick="RemoveWishlist({{ $product->id }})"  class="default-btn"><i class="fa fa-heart" aria-hidden="true" style="color:#ff0000;"></i></a>
@else
<a  href="javascript:void(0)" onclick="addToWishlist({{ $product->id }})"  class="default-btn"> <i class="fa fa-heart-o" aria-hidden="true"></i></a> 
 @endif   
</span> 
 @endif
<span id="active_wish" style="display:none;"><a href="javascript:void(0)" onclick="RemoveWishlist({{ $product->id }})"  class="default-btn"><i class="fa fa-heart" aria-hidden="true" style="color:#ff0000;"></i></a></span>
<span id="inactive_wish"  style="display:none;"><a  href="javascript:void(0)" onclick="addToWishlist({{ $product->id }})"  class="default-btn"> <i class="fa fa-heart-o" aria-hidden="true"></i></a> </div>


@endif

          @if($product->status=="active" && $is_active_subscriber )     
                <div class="share-this-product" style="display:block;clear:both;">
                  <ul>
                    <li> <span>Share</span> </li>
                    <li> <a href="http://www.facebook.com/sharer.php?u={{Request::url()}}" target="_blank"> <i class="fa fa-facebook" aria-hidden="true"></i>
 </a> </li>
                    <!-- <li> <a href="https://www.instagram.com/" target="_blank"> <i class="ri-instagram-line"></i> </a> </li> -->
                    <li> <a href="http://www.linkedin.com/shareArticle?mini=true&url={{Request::url()}}" target="_blank"> <i class="fa fa-linkedin" aria-hidden="true"></i>
 </a> </li>
                    <li> <a href="http://twitter.com/share?url={{Request::url()}}" target="_blank"> <i class="fa fa-twitter" aria-hidden="true"></i>
 </a> </li>
                  </ul>




                </div>
        
        @endif
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
                
                <li> Reviews </li>
                
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
							if(!empty($user_1->address)) echo $user_1->address."<br>";
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
                      @if(!empty($product->available_countries))    
                          <tr>
                           
                          <td>Product avaialable in:</td>
                          <td> @if(!$is_active_subscriber ||$usertype=="guest") '****'
								@else                      
                          
									@if(!empty($product->available_countries))
										<i class="fa fa-map-marker" aria-hidden="true"></i> 
											<?php $i = 1;  ?>
												@foreach ($countries as $country)
													<?php $selectedCountries = explode(",",$product->available_countries); $len = count($selectedCountries); ?>
														@if(in_array($country->id, $selectedCountries))
														{{ $country->name }}
													<?php if($i < $len){echo ',';} $i++;?>
														@endif 
			  
												@endforeach

									@endif 
							@endif                       
                          </td>
                        </tr>
					@endif        
                          @if(!empty( $product->label_language))
                         <tr>
                          <td>Etiket / label language:</td>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' :$product->label_language ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty( $product->SKU))
                         <tr>
                          <td>SKU:</td>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' :$product->SKU ?? 'N/A' }}</td>
                        </tr>
						@endif
                        @if(!empty($product->product_condition))						
                        <tr>
                          <td>Condition:</td>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' :$product->product_condition ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty($product->product_color))
                        <tr>
                          <td>Color: </td>
                          <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->product_color ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty($product->product_size))
                        <tr>
                          <td>Size:</td>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->product_size ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty($product->product_weight))
                        <tr>
                          <td>Weight:</td>
                          <td> {{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->product_weight ?? 'N/A' }} </td>
                        </tr>
						@endif
						@if(!empty($product->product_dimension))
                        <tr>
                          <td>Dimensions: </td>
                          <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->product_dimension ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty($product->location))
                        <tr>
                          <td>Location: </td>
                          <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->location ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty($product->feature ))
                        <tr>
                          <td>Features: </td>
                          <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->feature ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty($product->brix))
                        <tr>
                          <td>Brix(%): </td>
                          <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->brix ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty($product->packaging))
                        <tr>
                          <td>Packaging: </td>
                          <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->packaging ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty( $product->place_of_origin))
                        <tr>
                          <td>Place of origin: </td>
                          <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->place_of_origin ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty($product->model_number))
                        <tr>
                          <td>  Model Number</td>
                          <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->model_number ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty($product->primary_ingredients))
                        <tr>
                          <td>Primary ingredients: </td>
                          <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->primary_ingredients ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty($product->additives))
                        <tr>
                          <td>Additives: </td>
                          <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->additives ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty( $product->flavor))
                        <tr>
                          <td>Flavor: </td>
                          <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->flavor ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty( $product->certification))
                        <tr>
                          <td>Certification: </td>
                          <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->certification ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty($product->volume))
                        <tr>
                          <td>Volume: </td>
                          <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->volume ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty($product->BBD))
                        <tr>
                          <td>BBD: </td>
                          <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $product->BBD ?? 'N/A' }}</td>
                        </tr>
						@endif
						@if(!empty( $product->product_expiry))
                        <tr>
                          <td>Expiry date: </td>
                          <?php $exp_date =  date("d-m-Y", strtotime($product->product_expiry)); ?>
                          <td>{{ (!$is_active_subscriber ||$usertype=="guest") ? '****' : $exp_date ?? 'N/A' }}</td>
                        </tr>
                        @endif
                        
                        
                      </tbody>
                    </table>
                  </div></div>
                  
                    
                    
                  </div>
                </div>
                 
                <input type="hidden" id="start_from" value="0">
                <div class="tabs_item">
                  <div class="products-details-tab-content">
                    <div class="product-review-form">
                      <div class="review-comments" id="divreviews">
                      </div>



<?php if(!$is_active_subscriber ||$usertype=="guest")  echo 'Sorry you are not allowed to write review';
else {
					  ?>


                      @if(!empty($user->Subscription))


                      <div class="review-form">
            
            
            @if($user_review_exists == "true")
              <p style="color:green; font-weight:bold;">You Submitted Your Review for This Item!</p>
          @else  
            
            
            
            
@if($product->status=="active" && $logged_id != $seller->id )             
            
            
                        <h3>Write A Review</h3>
                        

@if(Session::has('message'))
<p class="alert {{ Session::get('success-class', 'alert-success') }}">{{ Session::get('message') }}</p>
@endif
          <form method="post" action="{{ route('review.submit') }}" >
                    @csrf
                  <div class="row">
          
                  <input type="hidden"  name="product_id" value="{{$product->id}}">
                 
                  
                  <div class="col-lg-6 col-12">
                      <div class="form-group">
                        <label>Name *</label>
                        <input type="text" value="{{old('name'),$user->name}}"  name="name" placeholder="Enter your name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" required>
                        @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                        @endif
                    </div>
                    </div>
                    <div class="col-lg-6 col-12">
                      <div class="form-group">
                        <label>Email * </label>
                        <input type="email"  value="{{old('email'),$user->email}}" id="email" name="email" placeholder="Enter your email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" required>
                        @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                    </div>
                    </div>
                    <div class="col-lg-12 col-12">
                      <div class="form-group">
                        <label>Review Title * </label>
                        <input type="text"  value="{{old('review_title')}}" id="review-title" name="review_title" placeholder="Review Title" class="form-control {{ $errors->has('review_title') ? ' is-invalid' : '' }}" required>
                        @if ($errors->has('review_title'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('review_title') }}</strong>
                                    </span>
                                @endif
                    </div>
                    </div>
                    <div class="col-lg-12 col-12">
                      <div class="form-group">
                        <label>Review * </label>
                        <textarea name="review"  id="review-body" rows="4" placeholder="Review" class="form-control {{ $errors->has('review') ? ' is-invalid' : '' }}" required="required">{{old('review')}}</textarea>
                        @if ($errors->has('review'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('review') }}</strong>
                                    </span>
                                @endif
                    </div>
                    </div>
                    <div class="col-lg-12 col-12">
                      <div class="form-group">
                        <label>Rating  </label>               
                   <div class="rating-css">
    <div class="star-icon">
        <input type="radio" value="1" name="star_rating" id="rating1">
        <label for="rating1" class="fa fa-star"></label>
        <input type="radio" value="2" name="star_rating" id="rating2">
        <label for="rating2" class="fa fa-star"></label>
        <input type="radio" value="3" name="star_rating" id="rating3">
        <label for="rating3" class="fa fa-star"></label>
        <input type="radio" value="4" name="star_rating" id="rating4">
        <label for="rating4" class="fa fa-star"></label>
        <input type="radio" value="5" name="star_rating" id="rating5">
        <label for="rating5" class="fa fa-star"></label>
    </div>
</div>
</div></div>

<style>
  .review-form form .form-group label {
  
    color: unset !important;
}
  </style>



                    <div class="col-lg-12">
                      <button type="submit" class="btn default-btn">Submit</button>
                    </div>
          
          
          
                  </div>
          </form>

@endif
@endif


















                      </div>
            
            @endif
            
            
<?php } ?>
            
            
            
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
<!-- End Product Details Area --> 
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Contact</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-bx">
          <div id="responsedata"></div>
        @if(Session::has('message'))
<p class="alert {{ Session::get('success-class', 'alert-success') }}">{{ Session::get('message') }}</p>
@endif
          <form method="get" id="productForm"  name="productForm"  >
                    @csrf
                    <input type="hidden"  name="product_id" value="{{$product->id}}">
                    <input type="hidden"  name="seller_id" value="{{$seller->id}}">
                    <input type="hidden"  name="seller_email" value="{{$seller->email}}">
                    <input type="hidden"  name="user_id" value="{{$logged_id ?? ''}}">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Name <span>*</span></label>
                
                  <input  value="{{ old('name', Auth::guard('user')->user()->name ?? '') }}"  type="text" id="name" name="name" placeholder="Enter your name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" required>
                        @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                        @endif
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Email  <span>*</span></label>
                  <input  value="{{ old('email', Auth::guard('user')->user()->email ?? '') }}"  type="email" id="email" name="email" placeholder="Enter your email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" required>
                        @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Phone  <span>*</span></label>
                  <input  value="{{ old('phone', Auth::guard('user')->user()->phone ?? '') }}"  type="text" id="review-title" name="phone" placeholder="Enter your phone number" class="form-control {{ $errors->has('phone') ? ' is-invalid' : '' }}" required>
                        @if ($errors->has('phone'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                               </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Message  <span>*</span></label>
                  <textarea name="message" id="review-body" rows="4" placeholder="Message" class="form-control {{ $errors->has('message') ? ' is-invalid' : '' }}" required="required">{{ old('message') }}</textarea>
                        @if ($errors->has('message'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('message') }}</strong>
                                    </span>
                                @endif
                              </div>
              </div>
              <div class="col-lg-12">
                <button type="submit" class="btn default-btn" id="saveBtn" >Send</button>
                <script>
               // var $fmcg = $.noConflict();
                  $fmcg('#saveBtn').click(function (e) { 
       // e.preventDefault();
        $fmcg(this).html('Sending..');
      
        $fmcg.ajax({
          data: $fmcg('#productForm').serialize(),
          url: "{{ route('seller.message') }}",
          type: "GET",
          dataType: 'json',
          success: function (data) { 
            $fmcg('#responsedata').html(data.success);
              $fmcg('#productForm').trigger("reset");
              setTimeout(function() {$fmcg('#exampleModal').modal('hide');}, 12000);
              table.draw();
           
          },
          error: function (data) {
              console.log('Error:', data);
              $fmcg('#saveBtn').html('Save Changes');
          }
      });
    });
                </script>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="modal-footer"> </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModa2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body"> ... </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>



@if(!empty($varients))
<section class="featured-products rltd-products">
  <div class="container">
    <div class="row wow fadeInUp">
      <div class="col-lg-12">
        <div class="main-title">
          <h2>Related Products</h2>
        </div>
      </div>
    </div>
    <div class="row wow fadeInUp">
      <div class="col-lg-12 col-12">
        <div class="row">
    @if(count($varients)>4) 
    
    <div class="related-products owl-carousel owl-theme">
    @foreach($varients as $product)
    <div class=" col-12 "> <a  href="{{ route('view.Sproduct',$product->id)}} ">
        <div class="product-thumbnail hovereffect">
          <div class="pro-img">
          <?php 
            $prd_img = URL::asset('/images/no-image.jpg');
            foreach($product->SellerProductImage as $prod_img) {
             if($prod_img->thumbnail=='yes')
             $prd_img = URL::asset('/uploads/productImages/'.$prod_img->image_path);
            }
            ?>
          <img src="{{ $prd_img }}"></div>
          <div class="product-title">
             <h3>{{ $product->name ?? ''}}</h3>
          
         
         
          @if($usertype!="guest" && $is_active_subscriber )  
           <h4> 
           @if(strtolower(trim($product->price_on_request)) != strtolower('Price on request')  )
                        <?php echo !empty($product->Currency->symbol) ? $product->Currency->symbol : '$' ?> <?php  echo rtrim(rtrim(number_format($product->product_price,3), '0'), '.'); ?> 
                        @if(strtolower(trim($product->price_negotiable)) == strtolower('Negotiable')) (Price Negotiable) @endif
                        @else
                        Price on Request @endif
           </h4>
            <div class="pro-no-loc">
              
              @if(!empty($product->country_name))
			 <h5><i class="fa fa-map-marker" aria-hidden="true"></i>{{ $product->country_name ?? '' }} </h5>
		 
                @endif
            </div>
          
          
          @endif
          
          
          
          
          </div>
          </div>
        </a> 
    </div>


      @endforeach
      
          </div>
      
      
      
      @else
        
      
      <div class="row" >
      
      
      <!--no courosel if count less than 4 -->
      @foreach($varients as $product)
      <div class="col-lg-3 ">
 <a  href="{{ route('view.Sproduct',$product->id)}} ">
        <div class="product-thumbnail hovereffect">
          <div class="pro-img">
          <?php 
            $prd_img = URL::asset('/images/no-image.jpg');
            foreach($product->SellerProductImage as $prod_img) {
             if($prod_img->thumbnail=='yes')
             $prd_img = URL::asset('/uploads/productImages/'.$prod_img->image_path);
            }
            ?>
          <img src="{{ $prd_img }}"></div>
          <div class="product-title">
            <h3>{{ $product->name ?? ''}}</h3>
           
            
            @if($usertype!="guest" && $is_active_subscriber )  
           <h4> 
           @if(strtolower(trim(old('price_on_request',$product->price_on_request))) != strtolower('Price on request'))
                        <?php echo !empty($product->Currency->symbol) ? $product->Currency->symbol : '$' ?> <?php  echo rtrim(rtrim(number_format($product->product_price,3), '0'), '.'); ?> 
                        @if(strtolower(trim($product->price_negotiable)) == strtolower('Negotiable')) (Price Negotiable) @endif
                        @else
                        Price on Request @endif
           </h4>
           @if(!empty($product->country_name))
            <div class="pro-no-loc">
              <h5><i class="fa fa-map-marker" aria-hidden="true"></i>{{ $product->country_name ?? '' }}   </h5>
		  </div>
		  @endif
		  @endif
          </div>
		  </div>
        </a> 
    </div>
      @endforeach
 </div>
      
      
      
      
      @endif
      
      
      
      </div>
    </div>
  </div>
</section>


@endif
<script src="{{asset('/js/image-zoom.js')}}" type="text/javascript"></script>
<script src="{{asset('/js/fa.js')}}"  type="text/javascript"></script>


<script type="text/javascript">

        $fmcg(document).ready( function () {
            $fmcg('#glasscase').glassCase({ 'thumbsPosition': 'bottom', 'widthDisplay' : 560});
            loadreviews();
        });
</script>


<script type="text/javascript">

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
    </script>

<script type="text/javascript">

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




 function loadreviews(){
  var product_id='<?php echo $productId;?>';
  
  
  $fmcg.ajax({
                            type : 'post',
                            url:"{{route('loadreview')}}",
                            data:{
                              _token:'{{ csrf_token() }}',
                              'product_id':product_id,
                              'start_from':$fmcg('#start_from').val(),
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
                                  $fmcg("#divreviews").append(string);
                                })
                              
                                   var skipcount=parseInt($fmcg("#start_from").val())+ initial ;
                                    if(dataItems.count!=skipcount)
                                      $fmcg("#divreviews").append('<br><div class="default-btn" style="width:15%;margin:0 auto" id="loadmore" onclick="loadmore()">Load More</div>');

                                   $fmcg("#start_from").val(skipcount);
         
                                   
                            }


                      });

}


function loadmore(){
  $fmcg("#loadmore").remove();
  loadreviews();
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

