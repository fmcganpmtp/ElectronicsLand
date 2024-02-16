@extends('admin.master')
@section('title', 'Seller View Product')
@section('breadcrumb') View Product @endsection
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
              @if($product->ProductImages)
						  @foreach ($product->ProductImages as $product_image)
              <li><img src="/uploads/SellerproductImages/{{ $product_image->image_path }}" /></li>
                @endforeach
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
                <h3> {{$product->name}}  ({{$product->product_color}}) </h3>
                <div class="product-review">
                  
                 <div class="price"> <span class="new-price"><?php echo !empty($product->Currency->symbol) ? $product->Currency->symbol : '$' ?>{{$product->product_price}}</span> 
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
                  <li> <span>Minimal order<b>:</b></span>{{$product->minimal_order}}</li>
                  <li> <span>Country<b>:</b></span>
                @foreach($countries as $country)
                {{$country}} , 
                @endforeach
                </li>
                <!--  <li> <span>Grade<b>:</b></span> New </li> -->
                  <li><span> Brand/Producer<b>:</b></span> {{ $product->Productbrand->name ?? 'N/A'}} </li>
                </ul>
                
             
                
                
                
                
              </div>
            </div>
          </div>

                </div>
         
                
        <div class="row tb ">
        
        <div class="col-lg-12">
        	<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Description </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Additional Information</a>
  </li>

</ul>
<div class="tab-content product-details-area " id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
  
  <div class="tabs_item">
                  <div class="products-details-tab-content">
                    <h3>Product Description</h3>
                    <p><?php echo $product->product_description ?$product->product_description : ""; ?></p>
                    </div>
                </div>
  
  </div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
  
  
  <div class="tab_content">
                
                <div class="tabs_item">
                  <div class="products-details-tab-content">
                    <ul class="additional-information">
                        
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

    

@endsection