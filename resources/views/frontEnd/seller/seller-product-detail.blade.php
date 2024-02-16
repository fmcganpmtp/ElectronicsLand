@extends('layouts.template')
@section('title', 'Product Details')
@section('content')
<link href="{{ asset('/css/image-zoom.css')}}" rel="stylesheet">

<section class="product-details-area ptb-54">
  <div class="container">
    <div class="row align-items-center">
      <div class="product-view-one">
        <div class="modal-content p-0">
          <div class="row align-items-center">
            <div class="col-lg-5"> 
              <ul id="glasscase" class="gc-start">
              
              <li><img src="https://hermosoftech-projects.in/fmcg/public/images/no-image.jpg" /></li>
             
                
              </ul>
           
            </div>
            <div class="col-lg-6">
              <div class="product-content ml-15">
                <h3> {{ $product->name ?? ""}} </h3>
                <div class="product-review">


                <?php
$rating = 0 ;
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




@if(!empty($avgStar))  <a href="#reviews" class="rating-count">{{ $Starcount }} Reviews</a> 
@else <span style='color:#ff0000;'>No Reviews Yet!</span>
@endif
</div>
               <?php 

               $stock_count = !empty($product->stock_count) ? $product->stock_count : 0;
               ?>
                  <div class="price"> <span class="new-price">${{ $product->product_price ?? ''}} </span>@if($stock_count>0 ) <span class="in-stock">In Stock (300 Items)</span> @else <span style="color:#ff0000;">&nbsp;Out Of Stock!</span>@endif </div>
                
                <ul class="product-info">
                  <li> <span>Minimal Order Quantity<b>:</b></span> <?php  $meta_title = isset($meta_title1) ? $meta_title1 : 'Beekaur';
 ?> 50 pairs</li>
                  
                  <li> <span>Available quantity<b>:</b></span>{{ $product->stock_count ?? ''}} </li>
                  <li style="display:block;clear:both;"> <span>Grade<b>:</b></span> New </li>
                  <li><span> Brand/Producer<b>:</b></span> {{ $product->Productbrand->name ?? '' }} </li>
                </ul>

                @if(empty($user->Subscription))
                <div class="product-detail-btn"> <a class="subscr default-btn" href="{{route('package.listing', ['pkg_type'=>'buyer'])}}"><i class="fa fa-telegram" aria-hidden="true"></i>Subscribe buyer plans to contact seller </a> </div>
                @endif
                
                <div class="share-this-product" style="display:block;clear:both;">
                  <ul>
                    <li> <span>Share</span> </li>
                    <li> <a href="http://www.facebook.com/sharer.php?u={{Request::url()}}" target="_blank"> <i class="ri-facebook-fill"></i> </a> </li>
                    <!-- <li> <a href="https://www.instagram.com/" target="_blank"> <i class="ri-instagram-line"></i> </a> </li> -->
                    <li> <a href="http://www.linkedin.com/shareArticle?mini=true&url={{Request::url()}}" target="_blank"> <i class="ri-linkedin-fill"></i> </a> </li>
                    <li> <a href="http://twitter.com/share?url={{Request::url()}}" target="_blank"> <i class="ri-twitter-fill"></i> </a> </li>
                  </ul>




                </div>
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
                <li> Description </li>
                <li> Additional Information </li>
                @if(!empty($user->Subscription))
                <li> Company information </li>
                @endif
                <li> Reviews </li>
                
              </ul>
            </div>
            <div class="col-lg-12 col-md-12">
              <div class="tab_content">
                <div class="tabs_item">
                  <div class="products-details-tab-content">
                    <h3>Product Description</h3>
                    <p>{{ $product->product_description ?? ""}}</p>
                  </div>
                </div>
                <div class="tabs_item">
                  <div class="products-details-tab-content">
              
                    
                    
                    
                    <div class="products-details-tab-content">
                       <div class="pro-table">
                    <table class="table table-bordered">
                
                      <tbody>
                        <tr>
                          <td>Condition:</td>
                          <td> New</td>
                        </tr>
                        <!--<tr>-->
                        <!--  <td>Color: </td>-->
                        <!--  <td>{{ $product->product_color ?? ""}}</td>-->
                        <!--</tr>-->
                        <!--<tr>-->
                        <!--  <td>Size:</td>-->
                        <!--  <td> {{ $product->product_size ?? ""}}</td>-->
                        <!--</tr>-->
                        <tr>
                          <td>Product Weight/Size:</td>
                          <td> {{ $product->product_weight ?? $product->product_size ?? ''}}</td>
                        </tr>
                        <!--<tr>-->
                        <!--  <td>Dimensions: </td>-->
                        <!--  <td>{{ $product->product_dimension ?? ""}}</td>-->
                        <!--</tr>-->
                        
                        
                        
                      </tbody>
                    </table>
                  </div></div>
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                  </div>
                </div>
                @if(!empty($user->Subscription))
                <div class="tabs_item">
                  <div class="products-details-tab-content">
                  
                     <div class="pro-table">
                    <table class="table table-bordered">
                      <tbody>
                        <tr>
                          <td>Company name</td>
                          <td> {{ $user->BuyerCompany->company_name?? ''}}</td>
                        </tr>
                        <tr>
                          <td>Name </td>
                          <td>  {{ $user->name?? ''}}</td>
                        </tr>
                        <tr>
                          <td>Surname</td>
                          <td> {{ $user->surname?? ''}}</td>
                        </tr>
                        <tr>
                          <td>Address </td>
                          <td> {{ $user->store_address?? ''}}</td>
                        </tr>
                        <tr>
                          <td>Zip Code </td>
                          <td> {{ $user->store_zip?? ''}}</td>
                        </tr>
                        <tr>
                          <td>Place </td>
                          <td> {{ $user->store_location?? ''}}</td>
                        </tr>
                        <tr>
                          <td>Land </td>
                          <td> {{ $user->store_city?? ''}} </td>
                        </tr>
                        <tr>
                          <td>Position </td>
                          <td> {{ $user->position?? ''}}</td>
                        </tr>
                        <tr>
                          <td>Languages  </td>
                          <td> {{ $user->BuyerCompany->languages_speak?? ''}}</td>
                        </tr>
                        
                         <tr>
                          <td>Opening hours </td>
                          <td>
                            @if(!empty($user->SellerOpeningTime))
                          <table>
                          @foreach ($user->SellerOpeningTime as $Spening)
        <tr>
            <td>{!! $Spening->day !!}</td>
            <td>{!! $Spening->opening_time !!}</td>
            <td>{!! $Spening->closing_time	 !!}</td>
        </tr>
    @endforeach
</table>
  @endif                        </td>
                        </tr>
                        <tr>
                          <td>Phone number </td>
                          <td> {{ $user->phone?? ''}}</td>
                        </tr>
                        
                        
                      </tbody>
                    </table>
                  </div></div>
                </div>
                @endif
                
                <div class="tabs_item">
                  <div class="products-details-tab-content">
                    <div class="product-review-form">
                      <h3>Customer Reviews</h3>
                      
                      @if(!empty($productReviews))
					  @foreach($productReviews as $productReview)
					  <div class="review-comments">
                        <div class="review-item">

<?php
$rating = 0 ;
if(!empty($productReview->star_rating))
$rating = $productReview->star_rating ;

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
                        
                           <h3>{{$productReview->review_title ?? '' }}</h3>
                         
                          <span><strong>{{$productReview->name ?? '' }}</strong> on <strong>{{ date('M d , Y', strtotime($productReview->created_at)) }}</strong></span>
                          <p>{{$productReview->review ?? '' }}</p>
                        </div>
                       
                        
                      </div>

@endforeach
@endif







                      @if(!empty($user->Subscription))


                      <div class="review-form">
					  
					  
					  @if($user_exists=="true")
						  <p style="color:green; font-weight:bold;">You Submitted Your Review for This Item!</p>
					@else  
					  
					  
					  
					  
					  
					  
					  
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
                        <input type="text" value="{{old('name')}}"  name="name" placeholder="Enter your name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}">
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
                        <input type="email"  value="{{old('email')}}" id="email" name="email" placeholder="Enter your email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}">
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
                        <input type="text"  value="{{old('review_title')}}" id="review-title" name="review_title" placeholder="Review Title" class="form-control {{ $errors->has('review_title') ? ' is-invalid' : '' }}">
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
                        <textarea name="review"  id="review-body" rows="4" placeholder="Review" class="form-control {{ $errors->has('review') ? ' is-invalid' : '' }}">{{old('review')}}</textarea>
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


















                      </div>
					  
					  @endif
					  
					  
					  
					  
					  
					  
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
          <form>
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Name</label>
                  <input type="text" id="name" name="name" placeholder="Enter your name" class="form-control">
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Email</label>
                  <input type="email" id="email" name="email" placeholder="Enter your email" class="form-control">
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Phone</label>
                  <input type="text" id="review-title" name="review-title" placeholder="Enter your phone number" class="form-control">
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label>Message</label>
                  <textarea name="review-body" id="review-body" rows="4" placeholder="Message" class="form-control"></textarea>
                </div>
              </div>
              <div class="col-lg-12">
                <button type="submit" class="btn default-btn">Send</button>
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



			

@endsection
@section('footer_script')
<!--newly added for file upload preview-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

<script src="{{asset('/js/image-zoom.js')}}" type="text/javascript"></script>




<script type="text/javascript">
//var $fmcg = $.noConflict();
                $fmcg(document).ready( function () {
            $fmcg('#glasscase').glassCase({ 'thumbsPosition': 'bottom', 'widthDisplay' : 560});
        });
     
    </script>
@endsection