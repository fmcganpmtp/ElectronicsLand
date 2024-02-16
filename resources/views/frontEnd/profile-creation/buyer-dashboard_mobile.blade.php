@extends('layouts.template_mobile')
@section('title', 'Dashboard')
@section('content')
<sectiion class="seller-page no-bg white-bg bottom-no-padding">
  <div class="container buyer-dtl55 buyyer-pageMb">
    <div class="row">
      <div class="col-lg-12">
        <div class="pr-bottom">
		<div class="card ">
            <div class="row">
              <div class="col-lg-12">
                <h3>Recently visited</h3>
              </div>
            </div>
			<div class="row">
			@if(!$prof_visited->isEmpty())
				@foreach($prof_visited as $data)
            
              <div class="col-lg-3 col-12">
                <a href="{{route('ViewSeller.profile',['profId'=>$data->profile_id])}}">
				<div class="seller-bxm">
				<?php if(!empty($data->profile_pic)) $img_path = asset('/uploads/userImages/').'/'.$data->profile_pic;
                    else  $img_path = asset('uploads/defaultImages/default_avatar.png');  ?>
                  <div class="pr-logo pr-logo-mb"><img src="{{ $img_path}}"></div>
                  <div class="seller-detail"> <b>{{ $data->name }} </b>
                    <p><?php $about = implode(' ', array_slice(explode(' ', $data->about), 0, 20)); ?>{{ $about ?? '' }} </p>
                  </div>
                </div>
				</a>
              </div>
           @endforeach
             
			 @else 
				 
				 <span style="color:#ff0000; display:block;clear:both;">No Profiles Visited Yet</span>
			 @endif
            
            </div>
          </div>
          <div class="card ">
            <div class="row">
              <div class="col-lg-12">
                <h3>Products looking for </h3>
              </div>
            </div>
            <div class="row">
			
				@if(!empty($prod_data))
				@foreach($prod_data as $data)
            
             <div class="col-lg-3 col-6 wow fadeInUp">
              
              <a  href="{{ route('view.Sproduct',$data['product']['product_id'])}}">
                <div class="product-thumbnail hovereffect">
				
				

					<?php  
					 $image_path='/images/no-image.jpg';
					if(!empty($data['thumbImg']['image_path']))
                    $image_path='/uploads/productImages/'.$data['thumbImg']['image_path']; ?>
				
				
				
				
				
                  <div class="pro-img"><a href="{{ route('view.Sproduct',$data['product']['product_id'])}}" ><img src="{{URL::asset($image_path)}}"></a></div>
                  <div class="product-title">
                    <h3>{{$data['product']['name']}} </h3>
                  </div>
                  
                </div></a>
              </div>
             @endforeach
             @else 
				 <span style="color:#ff0000; display:block;clear:both;">No Products Viewed Yet</span>
			 @endif
			
			
            </div>
          </div>
          <div class="wow fadeInUp">
         
			
			
           
            
      
            
       <div class="price-footer"> <!--<a class="default-btn" href="subscription-checkout.php">Renew now</a>-->
                   <a href="{{route('package.listing', ['pkg_type'=>'seller'])}}" class="become-a-seller-btn">
                       Become a seller
                       
                     <!-- <div class="bottom"></div>
<div class="top">
                        <div class="label">Become a seller</div>
                        <div class="button-border button-border-left"></div>
                        <div class="button-border button-border-top"></div>
                        <div class="button-border button-border-right"></div>
                        <div class="button-border button-border-bottom"></div>
                      </div-->
                      </a>

			  
			  
			  
			  
			  
			  
			  
			  
            
            </div>
          </div>
          
          
          
          
        </div>
      </div>
    </div>
  </div>
</sectiion>



@endsection

           
<script>
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