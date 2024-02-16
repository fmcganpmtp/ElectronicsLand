@extends('layouts.template')
@section('title', 'Add Product')
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
        <div class="pr-bottom ad-pro" >
          <div class="card">
            <div class="step-icon">
              <div class="step-img"><img src="assets/images/product-add.png"></div>
              <div class="step-count">3</div>
            </div>
            <h3>Add product</h3>
        
            <div class="row">
             
			 
			 
			  <form method="post" action="{{ route('insert.sellerProduct') }}" aria-label="{{ __('Update') }}"  enctype="multipart/form-data">
                @csrf
              <div class="row">
                  <div class=" col-lg-4  col-12">
                    <div class="form-group">
                      <label>Product name:</label>
                      <input type="hidden" name="id" value="{{ $product->id }}" >
                      <input type="text" class="form-control" name='name' value="{{ $product->name }}" required >
					   @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                    </div>
                  </div>
				   <div class=" col-lg-2 col-12"> <div class="form-group">
                  <label>Currency: <sup>*</sup></label>
                 <select name="currency_id" id="currency" class="form-control  {{ $errors->has('currency_id') ? ' is-invalid' : '' }}" aria-label="Default select example"  >
                    <option value="">Select</option>
                  @if(!empty($currencies))
                  @foreach ($currencies as $currency)
                  
                    <option  value="{{$currency->id}}">{{$currency->shortcode}}(<?=$currency->symbol?>)</option>
                    
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
                      <label>Product price:</label>
                      <input type="text"  class="form-control"  name="product_price" value="{{ $product->product_price }}" >
					  @if ($errors->has('product_price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('product_price') }}</strong>
                                    </span>
                                @endif
                    </div>
                  </div>
                    <div class=" col-lg-6  col-12">
                <div class="form-group">
                  <label> Category:</label>
                  <?php $selectedCategories = []; ?>
                  <select type="text"   id="category" name="category_id" class="form-control" >
                                        <option value="">None</option>
                                        @if($categories)
                                            @foreach($categories as $item)
                                            <?php $dash=''; ?>
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                                


                                            @endforeach
                                        @endif
                                    </select>
                </div>
              </div>
			  <div class=" col-lg-6  col-12  sub_cat" style="display:none;">
                <div class="form-group">
                  <label>Sub Category:</label>
                  <?php $selectedCategories = []; ?>
                  <select name="subcategory_id" id="subcategory" class="form-control input-sm">
                   <option value=""></option>
                  </select>
                </div>
              </div>
			  <div class=" col-lg-6  col-12 subsub_cat" style="display:none;">
                <div class="form-group">
                  <label>Sub Category:</label>
                  <?php $selectedCategories = []; ?>
                  <select name="subsubcategory_id" id="subsubcategory" class="form-control input-sm">
                     <option value=""></option>
                   </select>
                </div>
              </div>
                  <div class=" col-lg-4  col-12">
                    <div class="form-group">
                      <label>SKU number:</label>
                     <input id="SKU" type="text" class="form-control{{ $errors->has('SKU') ? ' is-invalid' : '' }}" name="SKU" value="{{ $product->SKU }}"  >

                                @if ($errors->has('SKU'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('SKU') }}</strong>
                                    </span>
                                @endif
                    </div>
                  </div>
                  <div class=" col-lg-4  col-12">
                    <div class="form-group">
                      <label>Stock count:</label>
                       <input id="stock_count" type="number" class="form-control{{ $errors->has('stock_count') ? ' is-invalid' : '' }}" name="stock_count" value="{{ $product->stock_count }}"  >

                                @if ($errors->has('stock_count'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('stock_count') }}</strong>
                                    </span>
                                @endif
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
                      <label>Weight</label>
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
                        <div  id="thumb_img_<?=$product_image->id?>">
                        
                         <a href="javascript:void(0)" onclick="fnremovegallaryimg({{ $product_image->id }},'tumb')" class=""><span class="red_round remove-input-field"><i class="fa fa-minus-circle" aria-hidden="true"></i></span></a>
                         <img src="/uploads/productImages/{{ $product_image->image_path }}" class="img-responsive" style="max-height: 100px; max-width: 100px;" alt="" srcset="">
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
                        
                        <div style="float:left;" id="gallery_img_<?=$product_image->id?>" >
                        <a href="javascript:void(0)" onclick="fnremovegallaryimg({{ $product_image->id }},'block')" class=""><span class="red_round remove-input-field"><i class="fa fa-minus-circle" aria-hidden="true"></i></span></a>

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
                                <?php $selectedCountries = explode(",",$product->available_countries); ?>
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
				
                  <input type="hidden" name="prev_imgs" value="" id="prev_imgs">
                <div class="form-group">
                  <label>Description:</label>
                  <textarea name="product_description" id="product_description" class="form-control{{ $errors->has('product_description') ? ' is-invalid' : '' }}">{{ $product->product_description }}</textarea>
                                @if ($errors->has('product_description'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('product_description') }}</strong>
                                    </span>
                                @endif
                </div>
                <button type="submit" class="bl-btn">Create</button>
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
<link rel="stylesheet" href="{{ asset('/css/select2.min.css')}}">
<script src="{{ asset('js/select2.min.js')}}"></script>
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




    var product_id = new Array();
$( document ).ready(function() {

 <?php foreach($product_id_list as $key => $val){ ?>
        product_id.push('<?php echo $val; ?>'); //js product image id array 
    <?php } ?>
    $("#prev_imgs").val(product_id); 
});  
a
function fnremovegallaryimg(id){
    product_id = product_id.filter(function(data){ 
            return data != id; 
        });
        $("#gallery_img_"+id).remove();
        $("#thumb_img_"+id).remove();
        
        $("#prev_imgs").val(product_id); 
}  


</script>


<script type="text/javascript">
$(document).ready(function () { 
            $('#category').on('change',function(e){
            console.log(e);
            var cat_id = e.target.value; 
            var bse_url = "{{URL::to('/')}}";
            $.get(bse_url+'/ajax-subcat?cat_id='+ cat_id,function(data){
                var subcat =  $('#subcategory').empty();
				subcat.append('<option value ="">Select</option>');
				if(data.length>0){
                $.each(data,function(create,subcatObj){
					$('.sub_cat').show();
                    var option = $('<option/>', {id:create, value:subcatObj});
                    subcat.append('<option value ="'+subcatObj.id+'">'+subcatObj.name+'</option>');
                });
				}
				else{
					$('.sub_cat').hide();
				}
				
            });
        });
		
		
		
		
		
		
		$('#subcategory').on('change',function(e){
            console.log(e);
            var cat_id = e.target.value; 
            var bse_url = "{{URL::to('/')}}";
            $.get(bse_url+'/ajax-subcat?cat_id='+ cat_id,function(data){
                var subcat =  $('#subsubcategory').empty();
				subcat.append('<option value ="">Select</option>');
				if(data.length>0){
                $.each(data,function(create,subcatObj){
					$('.subsub_cat').show();
                    var option = $('<option/>', {id:create, value:subcatObj});
                    subcat.append('<option value ="'+subcatObj.id+'">'+subcatObj.name+'</option>');
                });
				}
				else{
					$('.subsub_cat').hide();
				}
				
            });
        });
		
		
		
		
    });
</script>


@endsection