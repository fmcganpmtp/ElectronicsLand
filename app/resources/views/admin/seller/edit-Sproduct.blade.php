@extends('layouts.template')
@section('title', 'Edit Product')
@section('content')

<sectiion class="seller-page no-bg">
  <div class="pr-banner">
    <!--<img src="assets/images/pr-banner.jpg">-->
  </div>
  <div class="container">
    <div class="row"> </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="pr-bottom ad-pro edt-pro" >
          <div class="card">
            
            <h3>Edit product</h3>
      
            <div class="row">
              <form method="post" action="{{ route('update.SellerProduct') }}" aria-label="{{ __('Update') }}"  enctype="multipart/form-data">
                @csrf
              <div class="row">
                  <div class=" col-lg-4  col-12">
                    <div class="form-group">
                      <label>Product Description:</label>
                      <input type="hidden" name="id" value="{{ $product->id }}" >
                      <input type="text" class="form-control" name='name' value="{{ $product->name }}"  >
					   @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                    </div>
                  </div>
				  <div class=" col-lg-2 col-12"> <div class="form-group">
                  <label>Currency: <sup>*</sup></label>
                 <select name="currency_id" id="currency" class="form-control  {{ $errors->has('currency_id') ? ' is-invalid' : '' }}" aria-label=""  >
                    <option value="">Select</option>
                  @if(!empty($currencies))
                  @foreach ($currencies as $currency)
                  
                    <option {{ $product->currency_id==$currency->id? 'selected':'' }}  value="{{$currency->id}}">{{$currency->shortcode}}(<?=$currency->symbol?>)</option>
                    
                  @endforeach 
                  @endif
				   @if ($errors->has('currency_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('currency_id') }}</strong>
                                    </span>
                                @endif
                  </select>
                </div>
                    </div>
                    
                  <div class=" col-lg-4  col-12">
                    <div class="form-group">
                      <label>Price:</label>
                      <input type="text"  class="form-control"  name="product_price" value="{{ $product->product_price }}" >
					  @if ($errors->has('product_price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('product_price') }}</strong>
                                    </span>
                                @endif
                    </div>
                  </div>
                  <div class=" col-lg-4 col-12 price_request_outer">
                  <div class="form-group">
                    <label for="stock_count" >{{ __('Price on request:') }} </label>
                    <div class="form-field-style"form-field-style" >
                      <input type="checkbox" name="price_on_request" @if(strtolower(trim(old('price_on_request',$product->price_on_request)))==strtolower('Price on request')) checked @endif   class="price_request"  value="Price on request"> {{ __('Price on request') }} </div>
                  </div>
                </div>
                <div class=" col-lg-4 col-12 negotiable_outer">
                  <div class="form-group">
                    <label for="stock_count" >{{ __('Negotiable:') }} </label>
                    <div class="form-field-style"form-field-style" >
                      <input type="checkbox" name="price_negotiable" @if(strtolower(trim(old('price_negotiable',$product->price_negotiable)))==strtolower('Negotiable')) checked @endif   class="negotiable"  value="Negotiable"> {{ __('Negotiable') }} </div>
                  </div>
                </div>
                  <div class=" col-lg-4  col-12">
                    <div class="form-group">
                      <label> Category:</label>
                      <select type="text" name="category_id[]" class="form-control" multiple>
                                        <option value="">None</option>
                                        @if($categories)
                                        <?php $selectedCategories = explode(",",$product->category_id);?>
                                            @foreach($categories as $item)
                                            <?php $dash=''; ?>
                                                <option  @if( in_array($item->id,$selectedCategories)) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                                @if(count($item->subcategory))
                                                @include('admin/products/category-edit',['subcategories' => $item->subcategory])
                                                @endif


                                            @endforeach
                                        @endif
                                    </select>
                             @if ($errors->has('category_id'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('category_id') }}</strong>
                                 </span>
                             @endif
                    </div>
                  </div>
                  
                        <div class=" col-lg-6 col-12"><div class="form-group">
                            <label for="SKU" >{{ __('SKU:') }} </label>
 
                            <div >
                                <input id="SKU" type="text" class="form-control" name="SKU" value="{{ $product->SKU }}" >

                            </div>
                        </div></div>
                        
                         <div class=" col-lg-6 col-12"><div class="form-group">
                            <label for="EAN_GTIN" >{{ __('EAN/GTIN:') }} </label>
 
                            <div >
                                <input id="EAN_GTIN" type="text" class="form-control" name="EAN_GTIN" value="{{ $product->EAN_GTIN }}" >

                            </div>
                        </div></div>
                         <div class=" col-lg-6 col-12"><div class="form-group">
                            <label for="batch" >{{ __('Batch#:') }} </label>
 
                            <div >
                                <input id="batch" type="text" class="form-control" name="batch" value="{{ $product->batch }}" >

                            </div>
                        </div></div>
                  
                  <div class=" col-lg-6 col-12"><div class="form-group">
                            <label for="stock_count" >{{ __('Product Brand:') }} </label>
 
                            <div >
                            <select type="text" name="brands" class="form-control">
                                        <option value="">None</option>
                                        @if($Productbrand)
                                            @foreach($Productbrand as $item)
                                               <option value="{{$item->id}}" {{ $product->brands==$item->id? 'selected':'' }} >{{$item->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                             @if ($errors->has('brands'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('brands') }}</strong>
                                 </span>
                             @endif
                            </div>
                        </div></div>




                  <div class=" col-lg-4  col-12">
                    <div class="form-group">
                      <label>Available Stock:</label>
                       <input id="stock_count" type="text" class="form-control{{ $errors->has('stock_count') ? ' is-invalid' : '' }}" name="stock_count" value="{{ $product->stock_count }}"  >
                       <span id="txtstock_count"></span>
                    </div>
                  </div>
                  <div class=" col-lg-4  col-12">
                    <div class="form-group">
                      <label> Color:</label>
                      <input id="product_color" type="text" class="form-control{{ $errors->has('product_color') ? ' is-invalid' : '' }}" name="product_color" value="{{ $product->product_color }}"  >

                                @if ($errors->has('product_color'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('product_color') }}</strong>
                                    </span>
                                @endif
                    </div>
                  </div>
                  <div class=" col-lg-3  col-12">
                    <div class="form-group">
                      <label>Product Weight/Size</label>
                      <input id="product_weight" type="text" class="form-control{{ $errors->has('product_weight') ? ' is-invalid' : '' }}" name="product_weight" value="{{ $product->product_weight }}"  >

                                @if ($errors->has('product_weight'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('product_weight') }}</strong>
                                    </span>
                                @endif
                    </div>
                  </div>
                  <div class=" col-lg-3  col-12">
                    <div class="form-group">
                      <label>Size:</label>
                      <input id="product_size" type="text" class="form-control{{ $errors->has('product_size') ? ' is-invalid' : '' }}" name="product_size" value="{{ $product->product_size }}"  >

                                @if ($errors->has('product_size'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('product_size') }}</strong>
                                    </span>
                                @endif
                    </div>
                  </div>
                  <div class=" col-lg-3  col-12">
                    <div class="form-group">
                      <label>Dimension:</label>
                       <input id="product_dimension" type="text" class="form-control{{ $errors->has('product_dimension') ? ' is-invalid' : '' }}" name="product_dimension" value="{{ $product->product_dimension }}"  >

                                @if ($errors->has('product_dimension'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('product_dimension') }}</strong>
                                    </span>
                                @endif
                    </div>
                  </div>
                  <div class=" col-lg-3  col-12">
                    <div class="form-group">
                      <label>Variants:</label>
                      <select type="text" multiple  name="variants[]" id='variants' class="form-control{{ $errors->has('variants') ? ' is-invalid' : '' }}">
         @foreach ($varients as $varient)
          <?php $selectedvariants = explode(",",$product->variants);?>
          @if(in_array($varient->id, $selectedvariants))
                        <option value="{{ $varient->id }}" selected="true">{{ $varient->name }}</option>
                        @else
                        <option value="{{ $varient->id }}">{{ $varient->name }}</option>
                        @endif 
     @endforeach 
		 </select>
          
                                @if ($errors->has('variants'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('variants') }}</strong>
                                    </span>
                                @endif
                    </div>
                  </div>
                  <div class=" col-lg-6  col-12">
                  
                   	<div class="form-group">
                      <label>Thumbnail image:</label>
                  
        
                  
                  
                  <div class="out-dv">
				  
				   @if($product_images)
						@foreach ($product_images as $product_image)
                        @if($product_image->thumbnail=="yes")
                        <div id="image-tumb{{ $product_image->id }}">
                        <a href="javascript:void(0)" onclick="removeImage({{ $product_image->id }},'tumb')" class=""><span class="red_round remove-input-field"><i class="fa fa-minus-circle" aria-hidden="true"></i></span></a>

                         <img src="/uploads/productImages/{{ $product_image->image_path }}" class="img-responsive" style="max-height: 50px; max-width: 50px;" alt="" srcset="">
</div>
                         @endif
                        @endforeach
                        @endif
                  	

              <input type="file" id="input-file-now-custom-3" class="form-control m-2" name="product_image"  accept="image/png, image/gif, image/jpeg">
                               @if ($errors->has('product_image'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('product_image') }}</strong>
                                    </span>
                                @endif
                      </div>
                      
                          </div>
                      
                    </div>
   
                  
                  
                  
                  <div class=" col-lg-6  col-12" >
                  
                   	<div class="form-group">
                      <label>Gallery Images(multiple upload):</label>
                  
                  <div class="row">
                  
                  <div class="col-lg-5 col-12">
                  
				  @if($product_images)
						@foreach ($product_images as $product_image)
                        @if($product_image->thumbnail=="no")
                        <div style="float:left;">
                        
                        <div id="image-block{{ $product_image->id }}">
                        <a href="javascript:void(0)" onclick="removeImage({{ $product_image->id }},'block')" class=""><span class="red_round remove-input-field"><i class="fa fa-minus-circle" aria-hidden="true"></i></span></a>

                         <img src="/uploads/productImages/{{ $product_image->image_path }}" class="img-responsive" style="max-height: 50px; max-width: 50px;" alt="" srcset="">
                         </div></div>
                         @endif
                        @endforeach
                        @endif
				  
                  </div>
                  
                  
                    <div class="col-lg-7 col-12">
                      <input type="file" id="input-file-now-custom-3" class="form-control m-2" name="product_gallery[]"   accept="image/png, image/gif, image/jpeg" multiple>
                        @if ($errors->has('product_gallery'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('product_gallery') }}</strong>
                                    </span>
                                @endif
                    </div>
                  </div>
                 </div>
                  
                  
                  
                  
                  
                  
                    
                  </div>
                  
                  
                  
                  
                </div>
				
				<div class=" col-lg-3  col-12">
                    <div class="form-group">
                      <label>Available Countries:</label>
                       <select type="text" multiple placeholder="Available Countries" name="available_countries[]" id='available_countries' class="form-control{{ $errors->has('product_dimension') ? ' is-invalid' : '' }}">
                                @foreach ($countries as $country)
                                <?php
                                if(!empty($product->available_countries))
                                $selectedCountries = explode(",",$product->available_countries); 
                                else
                                $selectedCountries =[];
                                ?>
                                @if(in_array($country->id, $selectedCountries))
                               
                        <option value="{{ $country->id }}" selected="true" >{{ $country->name }}</option>
                        @else
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endif 
     @endforeach 
                                </select>
                                @if ($errors->has('available_countries'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('available_countries') }}</strong>
                                    </span>
                                @endif
                    </div>
                  </div>
				
				
                <div class="form-group">
                  <label>Description:</label>
                  <textarea name="product_description" id="product_description" class="form-control{{ $errors->has('product_description') ? ' is-invalid' : '' }}">{{ $product->product_description }}</textarea>
                                @if ($errors->has('product_description'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('product_description') }}</strong>
                                    </span>
                                @endif
                </div>
                <button type="submit" class="bl-btn">Update</button>
              </form>
            </div>
            <!--step-2-->
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
</sectiion>






@endsection
@section('footer_script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    var unlimited_stock='<?php echo $product->unlimited_stock;?>';
      if(unlimited_stock=='Unlimited')
        $(".stk_cnt").hide();
      else
        $(".stk_cnt").show();
      $(".unlimited").click(function() {
    if($(this).is(":checked")) {
        $(".stk_cnt").hide();
		
		$("#stock_count").val('');
    } else {
        $(".stk_cnt").show();
    }
});


 $(".price_request").click(function() {
        
      if($(this).prop("checked") == true){
                 $(".negotiable_outer").hide();
                 $(".negotiable").val('');

                    } else {
                         $(".negotiable_outer").show();
                    }
                });
   
   
    $(".negotiable").click(function() {
        
      if($(this).prop("checked") == true){
                 $(".price_request_outer").hide();
                         $(".price_request").val('');

                    } else {
                         $(".price_request_outer").show();
                    }
                }); 
});
                </script>
<script type="text/javascript">
        function removeImage(id = null,type){ 
            if(confirm('Do you want to remove image?')){
                if(id != null){
                    $.ajax({
                        type:'DELETE',
                        url:'{{ route("delete.SproductImage") }}',
                        data:{id: id, '_token':'{{csrf_token()}}'},
                        success:function(response){
                            if(response.result){

                                $('#image-block').replaceWith('<span class="text-success" id="alert_image">Image removed successfully.</span>');
                                $('#alert_image').delay(2000).fadeOut();
                                $('#image-'+type+id).remove();
                            } else {
                                alert(response.message);
                            }
                        }
                    });
                } else {
                    alert('Image remove failed. Something went wrong.');
                }
            }
        }
    </script>


<script type="text/javascript">

var route = "{{route('available.countries')}}";
  $('#available_countries').select2({
     placeholder: 'Select Available Countries',

    
    ajax: {
      url: route,
      dataType: 'json',
      delay: 250,
      processResults: function(data) {
        return {
          results: $.map(data, function(item) {
            return {
              html:"<span>"+item.name+"</span>",
              text: item.name,
              id: item.id
            }
          })
        };
      },
      cache: true,

    }
  });





  var route_varients = "{{route('autocomplete.product')}}";
  $('#variants').select2({
     placeholder: 'Select Varients',

   
    ajax: {
      url: route_varients,
      dataType: 'json',
      delay: 250,
      processResults: function(data) {
        return {
          results: $.map(data, function(item) {
            return {
              html:"<span>"+item.name+"</span>",
              text: item.name,
              id: item.id
            }
          })
        };
      },
      cache: true,

    }
  });




   


</script>


@endsection