@extends('layouts.template_mobile')
@section('content')
@section('title','FMCG LAND - Connecting the world of fmcg')
@section('description', 'Welcome to FMCG Land, the dedicated social business platform exclusively tailored for FMCG companies. Unlock unparalleled networking solutions and explore our highly relevant marketplace, designed to enhance collaboration and elevate business opportunities within the Fast-Moving Consumer Goods sector.')
@section('keywords', '')

@if(Session::has('message'))
<script>
	<?php if(Session::get('message')=="Email Verified") { ?>
	  var message="<?php echo Session::get('message');?>";
	swal(message, { icon: "success",buttons: false,timer: 7000 });
	<?php } else { ?>
	  var message="<?php echo Session::get('message');?>";
	swal(message, { icon: "error",buttons: false,timer: 7000 });
	<?php } ?>
</script> 
@endif

@if(!is_null($mobile_slider))
	<section class="sliderC">
		<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-pause="false">
			<?php  $count = 0;  ?>
			<div class="carousel-indicators">
				@foreach ($mobile_slider->MobileSliderimage as $Slider_img)
				<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{$count}}" class="@if($count==0)  active @endif" aria-current="true" aria-label="Slide {{$count}}"></button>
				<?php $count++; ?>
				@endforeach 
			</div>
			<?php  $count = 0;  ?>
			<div class="carousel-inner">
				@foreach ($mobile_slider->MobileSliderimage as $Slider_img)  
				<div class="carousel-item @if($count==0)  active @endif" data-bs-interval="5000">
					<img src="{{ URL::asset('/assets/uploads/sliders/'.$Slider_img->image)}}"     class="d-block w-100" alt="...">
					<div class="carousel-caption">
						    <h1>{{$Slider_img->title}}</h1>
                            <h2>{{$Slider_img->description}}</h2>
		                    @if($Slider_img->target!='')      
								<a href="{{$Slider_img->target}}" target="_blank" class="bg-button">SHOP NOW</a>
						    @endif 	
						<!--container-->
					</div>
				</div>
				<?php $count++; ?>
				@endforeach  
			</div>
			<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev"> <span class="carousel-control-prev-icon" aria-hidden="true"></span> <span class="visually-hidden">Previous</span> </button>
			<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next"> <span class="carousel-control-next-icon" aria-hidden="true"></span> <span class="visually-hidden">Next</span> </button>
		</div>
	</section>
	@endif
<!-- Start Popular Categories Area -->
<section class="pop-cat">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="main-title">
          <h2>POPULAR CATEGORIES</h2>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
      
        <div class="category-slider owl-carousel owl-theme">
             @if(!empty($all_categories))
				@foreach($all_categories as $category)
				<?php 
									$img_url =asset('/uploads/categoryImages/'.$category->category_pic); 
									if((is_null($category->category_pic)))
									$img_url = asset('uploads/defaultImages/pop-ic-4.png');   
				?>
		  <a href="{{route('Product.Listing',$category->slug)}}">		
		  <div class="product-cat-bx">
            <div class="cat-image"><img src="{{$img_url}}"></div>
            
            <div class="before-login-pro-dtl">
                  <h3>{{$category->name}}</h3>
                
            </div>
            
          
            
            
            
          </div>
		  </a>		
				
				@endforeach
				@endif
            </div>
       
      </div>
    </div>
  </div>
</section>
<section class="popular-offer-sec">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="main-title">
          <h2>MOST POPULAR OFFERS</h2>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <!--popular-offer-->  
        <div class="category-slider  owl-carousel owl-theme"> 
        
        @if(!empty($products))
			@foreach($products as $product)
		<?php 
								$prd_img = URL::asset('/images/no-image.jpg');
								foreach($product->SellerProductImage as $prod_img) {
                                  if($prod_img->thumbnail=='yes')
                                  $prd_img = URL::asset('/uploads/productImages/'.$prod_img->image_path);
                                 }
								 ?>	
		<a href="{{ route('view.Sproduct',$product->id)}}">
          <div class="product-bx">
            <div class="product-img"><img src="{{$prd_img}}" /></div>
            @if($validity!=true)
                        <div class="before-login-pro-dtl"><h3>{{ strlen($product->name) > 20 ? substr($product->name, 0, 20) . '...' : $product->name}} </h3></div>
            @elseif($validity==true)
			<div class="after-login-pro-dtl"><h3>{{ strlen($product->name) > 20 ? substr($product->name, 0, 20) . '...' : $product->name}}</h3>
							<h3>
								@if(strtolower(trim($product->price_on_request)) != strtolower('Price on request'))
								<?php 
								    $currency = $product->symbol??'$';
										 
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
								Price on Request @endif
							</h3>
							<div class="pro-no-loc">
								@if(!empty($product->User->country_id))
								
								
								<div class="">
								    <h3> <i class="fa fa-map-marker" aria-hidden="true"></i> {{ $product->User->Country->name }} </h3>
								    
								</div>
								
								@endif
							</div>
			</div>
			@endif 
          </div>
          </a> 	
			
			@endforeach
			@endif
        
          </div>
      </div>
    </div>
  </div>
</section>
<section class="featured-pro">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="main-title">
          <h2>FEATURED PRODUCTS </h2>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="category-slider  owl-carousel owl-theme"> 
        
          
          
          
          @if(!empty($featured_products))
						@foreach($featured_products as $product)
							<?php 
											$prd_img = URL::asset('/images/no-image.jpg');
											foreach($product->SellerProductImage as $prod_img) {
											 if($prod_img->thumbnail=='yes')
											 $prd_img = URL::asset('/uploads/productImages/'.$prod_img->image_path);
											} ?>
						
						<a href="{{ route('view.Sproduct',$product->id)}}">
          <div class="product-bx">
            <div class="product-img"><img src="{{ $prd_img }}" /></div>
            @if($validity!=true)
            	<div class="before-login-pro-dtl">            <h3>{{ strlen($product->name) > 20 ? substr($product->name, 0, 20) . '...' : $product->name}}
            	     </h3> </div>
            
            @elseif($validity==true)
			<div class="after-login-pro-dtl">            <h3>{{ strlen($product->name) > 20 ? substr($product->name, 0, 20) . '...' : $product->name}} </h3> 
											<h3> @if(strtolower(trim($product->price_on_request)) != strtolower('Price on request'))
												<?php
													$currency = '$';
													if(!empty($product->Currency->symbol))
													    $currency = $product->Currency->symbol;
													$prod_price = $product->product_price;
													$no_of_dec =0;
													if (strpos($prod_price, ".") !== false) {
													     $v_fileFloatb = explode(".", $prod_price);
													     $no_of_dec = strlen($v_fileFloatb[1]);    }
													if($no_of_dec==1)
													 echo $currency.'&nbsp;'.rtrim(rtrim(number_format($product->product_price,3), '0'), '.').'0';
													else 
													 echo $currency.'&nbsp;'.rtrim(rtrim(number_format($product->product_price,3), '0'), '.');  ?>
												@if(strtolower(trim($product->price_negotiable)) == strtolower('Negotiable')) (Price Negotiable) @endif
												@else
												Price on Request @endif
											</h3>
											<div class="pro-no-loc">
												@if(!empty($product->User->country_id))
												<h3> <i class="fa fa-map-marker" aria-hidden="true"></i>
													{{ $product->User->Country->name }}  
												</h3>
												@endif
											</div>
										</div>
			@endif 
          </div>
          </a> 
						@endforeach
						@endif
          
          
    
          
           </div>
      </div>
    </div>
  </div>
</section>
<sectioon class="home-registration">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
       <?php 
	if(!Auth::guard('user')->check())
	  $user_type_status="Not_register";
	else if(Auth::guard('user')->check())
	{
	  if(Auth::guard('user')->user()->usertype=="guest")
	    $user_type_status="Not_register";
	  else
	     $user_type_status="registered";
	}
	if($user_type_status=="Not_register"){ ?>   
        <h2>JOIN OUR GROUP OF SATISFIED USERS<br/>
          <b>REGISTER NOW FOR FREE!</b> </h2>
        <div class="btn-c"> <a href="{{ route('user-register') }}">I WANT TO SELL</a> <a href="{{ route('user-register') }}">I WANT TO BUY</a> </div>
      <?php } else{?>
      
      <h4>Welcome to FMCG Land</h4>
							<h5>
								The go-to destination to expand<br/>
								your business like nowhere else.
							</h5>
							<h6>
								Enjoy exploring our platform!
		<?php } ?>					</h6>
      
      </div>
    </div>
  </div>
</sectioon>





@if((count($productRequests)>0) )
<section class="product-req">
  <div class="container">
    <div class="row wow fadeInUp" >
      <div class="col-lg-12">
        <div class="main-title">
          <h2>product requests</h2>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="product-req-slider owl-carousel owl-theme">
  
		@foreach($productRequests as $data)
        <div class=" col-12 ">
          <div class="product-thumbnail hovereffect">
              <?php
              $prd_img = URL::asset("/images/no-image.jpg");
              if(!empty($data->prod_img))
                $prd_img = URL::asset("/uploads/productRequests/".$data->prod_img); ?>
            <div class="pro-img"> <img src="{{$prd_img}}"> </div>
            <div class="product-title">
              <h3>{{ strlen($data->prd_name) > 30 ? substr($data->prd_name, 0, 30) . '...' : $data->prd_name}}</h3>
              <div class="after_login_01">
                @if($data->company_name!='' && Auth::guard('user')->check())
                    <h3>{{$data->company_name}}</h3>
                @endif 
                <div class="pro-no-loc">
                  <h5> 
                @if($data->country!='')
                 <i class="fa fa-map-marker" aria-hidden="true"></i>{{$data->country}}
                @endif
                </h5>
                </div>
                @if(Auth::guard('user')->check())
                <div class="req-btns">
                @if( $view_composer_profile_menu_visible_criteria['general_companyProfile'] == 1)
                  <a target="_blank" href="{{route('ViewSeller.profile',$data->parent_id)}}" class="req-bl-btn">View Profile</a> 
                 @else
                  <a  href="javascript:void(0)" onclick="showerrorprofile()" class="req-bl-btn">View Profile</a> 
                 @endif
                 @if( $view_composer_profile_menu_visible_criteria['network_chat'] == 1)
                <a href="javascript:void(0)" onclick="EnableChatRequest({{$data->user_id}})"  class="req-bl-btn">Message</a> 
                @else
                <a href="javascript:void(0)"  class="req-bl-btn"  onclick="showerrorchat()" >Message </a>
                @endif
                </div>
                @endif
 <div class="product-ms-over">
    <div class="pr-hover-border-bx"><div class="req-contet">
      
        <h4>We are looking for:</h4>
       
        <div class="ms-over-txt">
            <p>{{strlen($data->prd_name) > 20 ? substr($data->prd_name, 0, 20) . '...' : $data->prd_name}}</p>
        </div>
        <h5>@if($data->quantity!='')
        Quantity : {{$data->quantity}}
         @endif </h5>
         <h6> @if($data->language!='')
        language : {{$data->language}}
        @endif </h6>
        
    </div></div>
</div>               
                
                
                
                
                
                
                
                
              </div>
            </div>
          </div>
        </div>
        @endforeach
        
      </div>
    </div>
   @if((count($productRequests)>0) && ($view_composer_profile_menu_visible_criteria['market_productRequests'] == 1))
    <div class="row">
      <div class="col-lg-12"> <a href="{{route('Product.Requests')}}" class="view-all-link">view all product requests</a> </div>
    </div> 
  @else
     
    <div class="row">
      <div class="col-lg-12"> <a href="javascript:void(0)" onclick="showerrorPrdReq()" class="view-all-link">view all product requests</a> </div>
    </div> 
   @endif
  </div>
</section>
@endif




<section class="section-green">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h2>FMCG Land. What's in it for you.</h2>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6 col-12">
        <ul>
          <li>Become part of the fastest growing B2B community in the FMCG industry and get instant access to a world full of opportunities</li>
           <li> No more long searchings on Google/internet in order to expand your business</li>
           <li> Get access to FMCG Land's Marketplace with over 30.000 products, representing all FMCG categories</li>
        </ul>
      </div>
	  
	   <div class="col-lg-6 col-12">
        <ul>
	  <li>Unique and innovative B2B platform that takes networking to the next level</li>
	  <li>	Connect & chat with FMCG professionals in over 100 countries</li>
		  <li>No commissions or hidden fees</li>
		  <li>All FMCG Land users are Verified Members</li>
        </ul>
      </div>
	  
	  
    </div>
	
	<div class="row">
		<div class="col-lg-12">
			<a href="{{ route('package.listing')}}" class="golden-btn-2">GET YOUR PERSONAL PLAN, FROM 'FREE' TO 'PREMIUM', HERE</a>
		</div>
	</div>
	
  </div>
</section>




@if(count($season_offers)>0)
<section class="special-offers">
	<div class="container">
		<div class="row wow fadeInUp">
			<div class="col-lg-12">
				<div class="main-title">
					<h2>special season offers</h2>
				</div>
			</div>
		</div>
		<div class="row wow fadeInUp">
			
			<div class="col-lg-12 col-12">
				<div class="row">
					<div class="special-season-slider owl-carousel owl-theme">
						
						@foreach($season_offers as $product)
						<div class=" col-12 ">
							<a  href="{{ route('view.Sproduct',$product->id)}} ">
								<div class="product-thumbnail hovereffect">
									<div class="pro-img">
										<?php 
											$prd_img = URL::asset('/images/no-image.jpg');
											foreach($product->SellerProductImage as $prod_img) {
											 if($prod_img->thumbnail=='yes')
											 $prd_img = URL::asset('/uploads/productImages/'.$prod_img->image_path);
											} ?>
										<img src="{{ $prd_img }}">
									</div>
									<div class="product-title">
										<h3>{{ strlen($product->name) > 15 ? substr($product->name, 0, 15) . '...' : $product->name}}  </h3>
										@if($validity==true)
										<div class="after_login_01">
											<h4> @if(strtolower(trim($product->price_on_request)) != strtolower('Price on request'))
												<?php
													$currency = '$';
													if(!empty($product->Currency->symbol))
													    $currency = $product->Currency->symbol;
													$prod_price = $product->product_price;
													$no_of_dec =0;
													if (strpos($prod_price, ".") !== false) {
													     $v_fileFloatb = explode(".", $prod_price);
													     $no_of_dec = strlen($v_fileFloatb[1]);    }
													if($no_of_dec==1)
													 echo $currency.'&nbsp;'.rtrim(rtrim(number_format($product->product_price,3), '0'), '.').'0';
													else 
													 echo $currency.'&nbsp;'.rtrim(rtrim(number_format($product->product_price,3), '0'), '.');  ?>
												@if(strtolower(trim($product->price_negotiable)) == strtolower('Negotiable')) (Price Negotiable) @endif
												@else
												Price on Request @endif
											</h4>
											<div class="pro-no-loc">
												@if(!empty($product->User->country_id))
												<h5> <i class="fa fa-map-marker" aria-hidden="true"></i>
													{{ $product->User->Country->name }}  
												</h5>
												@endif 
											</div>
										</div>
										@endif 
									</div>
								</div>
							</a>
						</div>
						@endforeach
						
					</div>
				</div>
			</div>
			
		</div>
	</div>
</section>

@endif




@if (count($newest_company) > 0)
<section class="partner-section">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="main-title">
          <h2>NEWLY JOINED COMPANIES </h2>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="partner-slider owl-carousel owl-theme">
            @foreach ($newest_company as $company)
				@php
				$cmp_img = asset('uploads/BuyerCompany/').'/'.$company->company_image;
				@endphp
				<a href="{{route('ViewSeller.profile',$company->user_id)}}">
				<div class="partner-logo-C"><img src="{{$cmp_img}}"></div>
				</a>
				@endforeach
        </div>
      </div>
    </div>
  </div>
</section>

@endif


@if (count($site_testimonials) > 0) 
<sectioon class="testimonialC">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="main-title">
          <h2>WHAT OUR CUSTOMERS ARE SAYING</h2>
          </h2>
        </div>
        <div class="testimonial-s owl-carousel owl-theme">
         @foreach($site_testimonials as $site_testimonial)
         <div class="testiBxx">
            <div class="testicontent">
              <p>{{$site_testimonial->comments}} </p>
            </div>
            <div class="testi-bottom">
                <?php
									if(!empty($site_testimonial->profile_pic)) 
									$img_path = asset('/assets/uploads/testimonials/').'/'.$site_testimonial->profile_pic;
									else  $img_path = asset('uploads/defaultImages/default_avatar.png'); ?>
              <div class="testi-ic"><img src="{{ URL::asset($img_path)}}" /></div>
              <h3>{{$site_testimonial->name ?? ''}}</h3>
              <h4>{{$site_testimonial->title ?? ''}}</h4>
              <h4>{{$site_testimonial->company_name ?? ''}}</h4>
              	@if(!is_null($site_testimonial->star_rating))
								<div class="star-ratings">
									<ul class="rating">
										<li> <i @if($site_testimonial->star_rating >= 1) class="fa fa-star" @else class="fa fa-star-o" @endif ></i> </li>
										<li> <i @if($site_testimonial->star_rating >= 2) class="fa fa-star" @else class="fa fa-star-o" @endif></i> </li>
										<li> <i @if($site_testimonial->star_rating >= 3) class="fa fa-star" @else class="fa fa-star-o" @endif></i> </li>
										<li> <i @if($site_testimonial->star_rating >= 4) class="fa fa-star" @else class="fa fa-star-o" @endif></i> </li>
										<li> <i @if($site_testimonial->star_rating >= 5) class="fa fa-star"  @else class="fa fa-star-o" @endif></i> </li>
									</ul>
								</div>
								@endif
            </div>
          </div>
         @endforeach
         
         
         
          
        </div>
      </div>
    </div>
  </div>
</sectioon>
@endif

	<?php  if(!Auth::guard('user')->check()) {  ?>
	<!-- Start Subscribe Area -->
	<section class="subscribe-area ptb-54 wow fadeInUp">
	  <div class="container">
		<div class="row align-items-center">
		  <div class="col-lg-7">
			<div class="subscribe-content">
			  <h3>Subscribe To Our Newsletter</h3>
			  <p>The latest offers, the best deals and everything that is happening in the world of FMCG.</p>
			</div>
		  </div>
		  <div class="col-lg-5">
		   
			
			<div class="newsletter-form">
						<input name="email" id="email" type="text" class="form-control {{ $errors->has('email')? ' is-invalid':''}}" placeholder="Enter Your Email" required >
						@if ($errors->has('email'))
						<span class="invalid-feedback" role="alert">
						<strong>{{ $errors->first('email') }}</strong>
						</span>
						@endif
						<button class="submit-btn default-btn" id="submit_news" type="submit"> <i class="fa fa-paper-plane" aria-hidden="true"></i> </button>
					</div>
					<div id="newsletter_msg" style="color:green;">
						<p></p>
					</div>
		  </div>
		</div>
	  </div>
	</section>
	<?php } ?>


@endsection
@if(!session()->has('modal'))
@if($view_composer_profile_menu_visible_criteria['flag_blocked_active'] == true)
<div class="modal email-modal" id="Mymodal" tabindex="-1" aria-labelledby="MymodalLabel1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="MymodalLabel1" style="text-align:center;">Verify KYC</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="hidepopemailverify()">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="modal-txt"></div>
          <p>
          Your account is suspended because of KYC rejection.</p>
        </div>

      </div>
      <div class="modal-footer">
       
      </div>
    </div>
  </div>
<script type="text/javascript">
 var modal = document.getElementById("Mymodal"); 
     modal.style.display = "block";
 function hidepopemailverify(){
     var modal = document.getElementById("Mymodal");
     modal.style.display = "none";
 }

</script>
{{ session()->put('modal','shown') }}
  @endif
  @endif
 