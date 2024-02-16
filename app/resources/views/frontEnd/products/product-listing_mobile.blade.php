@extends('layouts.template_mobile')
@section('title', 'Product Listing')
@section('content')

<!-- Start Page Title Area -->

<link href="https://hermosoftech-projects.in/fmcg/public/css/extra.css">
<div class="page-title-area">
  <div class="container">
    <div class="page-title-content brd-crub">
      <ul>
        <li><a href="{{ route('home')}}"> Home</a></li>
        <li class="active">{{$category_Name_fr_slug}}</li>
      </ul>
      
         <div class="filter-btn"><a onclick="openNav2()" ><i class="fa fa-filter" aria-hidden="true"></i>
 </a></div>
    </div>
  </div>
</div>
<!-- Start Product Area -->
<section class="products-area inner-page-C ">
    
    
    
<div id="mySidenav2" class="sidenav2"> <a href="javascript:void(0)" class="closebtn" onClick="closeNav2()"><i class="fa fa-times" aria-hidden="true"></i>
</a>


<div class="product-filter-bx">
    
    
        <div class="filter-inner">
            
            
                <div class="widget-sidebar mr-15">
          @if (in_array("Keywords", $search_conditions))
          <div class="sidebar-widget  borddr-bx1 categories tpp">
              <h3>Search by Keyword</h3>
          
                <div class="autocomplete form-group sg-list" >
                  <div class="srch-left-3">
                <input type="text" name="keyword" id='search_keyword' class="form-control"  placeholder="Search">
                <input type="hidden" name="topcategorysearch" id='topcategorysearch' value="{{$search_key}}">
                        <button type="submit" class="search_keyword_icon"><i class="fa fa-search" aria-hidden="true"></button></i>
                </div>
         </div>
          </div>
          

          @endif
         @if (in_array("Category", $search_conditions))
             @if(count($left_menu_list)>0)
                <div class="sidebar-widget borddr-bx1 categories">
                    
                    <h3>Categories</h3>
                     <div class="srch-left-3"><input type="text" class="category_search" placeholder="Search" /><button type="submit" class="category_search_icon"><i class="fa fa-search" aria-hidden="true"></button></i></div>

                  <ul id="ulcatgory">
                    
                    @foreach($left_menu_list as $data)
                    <li><input type="checkbox" class="chkcategory"  onclick="fnPushcategoryId({{$data['id']}})" value="{{$data['id']}}"> &nbsp;&nbsp;{{$data['name']}}</li>
                    @endforeach
                  </ul>
                </div>
               @endif
          @endif

            @if (in_array("Price filter", $search_conditions)  && (Auth::guard('user')->check()))
          <div class="sidebar-widget filter borddr-bx1  price-ranger01">
            <h3>Filter By Price</h3>
           
          <div class="grey_slide" style="display:none;">
              <img src="{{ asset('images/grey_slid.jpg')}}" />
           </div>
                   <div class="price-fi-out">
              <input type="text" id="p-range" class="js-range-slider" name="my_range"  value="" data-skin="round" data-type="double" data-min="0"  data-max={{$max_product_price}}  block="true"   disable="true" />
                 </div>  
                   
                   <div class="row price-fliter-Box">
                   
                   <div class="col-lg-6 col-12">
                   <label>Price From :</label>
                   <input type="text" id="p-start"  placeholder="0" onblur="range_selfupdate()" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" class="price_from"  />
                  </div>
                  
                  <div class="col-lg-6 col-12">
                   <label>Price To :</label>
                   <input type="text" id="p-end"  placeholder="{{$max_product_price}}" onblur="range_selfupdate()"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" class="price_to" />  
          
           </div>
          </div>
          
          
          <div class="row">
          <div class="col-lg-6 col-12">
          <label><input type="radio" class="chkPriceFilter" id="p-req-both" name="priceOnRequest" onclick="fnPushPriceFilter()" value="2"  style="width:auto !important;">&nbsp;Price Only</label>
          </div>
          <div class="col-lg-6 col-12">
            <label><input type="radio" class="chkPriceFilter" id="p-req-only" name="priceOnRequest" onclick="fnPushPriceFilter()" value="1" style="width:auto !important;">&nbsp;Price on Request</label>
                   
        </div>
                   
      </div>
          
          </div>

            @endif

          @if (in_array("Brand", $search_conditions))
           @if(count($brand_lists)>0)
               <div class="sidebar-widget borddr-bx1 brand ">
                   
                  <h3>Brand</h3>
                    <div class="srch-left-3"><input class="brand_search" type="text" placeholder="Search"/><button type="submit" class="brand_search_icon"><i class="fa fa-search" aria-hidden="true"></button></i></div>
                  <ul id="ulbrands">
                   
                    @foreach($brand_lists as $data)
                    <li><input type="checkbox" class="chkbrand" onclick="fnPushBrandId({{$data['id']}})" value="{{$data['id']}}"> &nbsp;&nbsp;{{$data['name']}} ({{$data['count']}})</li>
                    @endforeach
                    </ul>
                </div>
            @endif
           @endif

              @if (in_array("Country", $search_conditions))
          <div class="sidebar-widget categories borddr-bx1  dontclosecategories">
               <h3>Country</h3>
            
            <div class="autocomplete form-group sg-list" >
      
              <select type="text" placeholder="Available Countries" name="available_countries[]" id='available_countries' class="form-control{{ $errors->has('product_dimension') ? ' is-invalid' : '' }}" multiple="multiple">
              </select>
              <input type="hidden" id="selected_country_id">
              <input type="hidden" id="start_from" value="0">
              <!--<button type="button"><i class="fa fa-search" aria-hidden="true"></i></button>-->
            </div>
          </div>

     @endif 
       @if (in_array("Product status", $search_conditions))
          @if(count($Trendingproducts)>0)  
          
          
                  <div class="filter-btn-out"> <a  href="javascript:void(0)" class="filter-bottom-btn" onClick="closeNav2()">Filter</a></div>

          <div class="trendingss borddr-bx1">
               <h3>Trending Products</h3>
            <ul class="trending-product-list">
            
            
              @foreach($Trendingproducts as $product)
             <?php 
             $image=$product->sellerproductimage;
              $image_path='/images/no-image.jpg';
            foreach( $image as $data){  
              if($data->thumbnail=='yes')  $image_path='/uploads/productImages/'.$data->image_path;}?>

              <li class="single-list"> <div class="single-list-img"><img src="{{URL::asset($image_path)}}" alt="Image"></div>
                <div class="product-content"> <a href="{{ route('view.Sproduct',$product->id)}}" class="title"> {{ ucfirst($product->name) ?? ""}} </a>
                  <ul class="products-price">
                       @if($validity )
                    <li> <?php  
                    
                    
                    
                    
                    $currency = '$';
                   if(!empty($product->Currency->symbol))
                   $currency = $product->Currency->symbol;
			       $prod_price = $product->product_price;
                    $no_of_dec =0;
                   if (strpos($prod_price, ".") !== false) {
                         $v_fileFloatb = explode(".", $prod_price);
                         $no_of_dec = strlen($v_fileFloatb[1]);   
                   }

                   if($product->price_on_request=="Price on request") 
                        echo "Price on request";
                   else{ 
                       if($no_of_dec==1)
                       echo $currency.'&nbsp;'.rtrim(rtrim(number_format($product->product_price,3), '0'), '.').'0';
                       else 
                       echo $currency.'&nbsp;'.rtrim(rtrim(number_format($product->product_price,3), '0'), '.');
                       if($product->price_negotiable=="Negotiable")
                        echo " (Negotiable)";
                   }
                    
                    
                    ?>  
                    
                    </li>
                    
                    
                    
                    
                    @endif
                  </ul>
                </div>
              </li>
              @endforeach
             </ul>
          </div>
           @endif
          @endif 
        </div>
        
                       

            
        </div>
    
    
</div><!--filter-bx-->
        
      

</div>
    
  <div class="container">
    <div class="row">
        
        

        
        
        
      <div class="col-lg-3">
               <!--   <div class="filter-btn"><a onclick="openNav2()" ><i class="fa fa-filter" aria-hidden="true"></i>
 filter</a></div>-->

    
      </div>


      <div class="col-lg-9 col-12 pr-listing">

        @if(count($central_listing)>0) 
            <div class="row"  id="divsubcategory pro-listing_mb">
                <div class="col-lg-12">
                  <div class="top-cat-bx">
                      <ul id="subcategory">
                         @foreach($central_listing as $val)
                            <li value="{{$val['id']}}"><a href="javascript:void(0)" onclick="fnsubcategory({{$val['id']}},this)">{{$val['name']}}</a></li>
                         @endforeach
                        </ul>
                    </div>
                </div>
            </div> 
        @endif

        <div class="row" id="product_list" class=""> </div>  
        <div class="default-btn load-btn" style="display: none;" id="loadmore" onclick="loadmore()">Load More...</div> 
      </div>
    </div>
  </div>
</section>
<style>

.load-btn{ display: table;
margin:10px auto;
cursor:pointer;}
 </style>

<!-- End Product Area -->
<!-- End Subscribe  Area -->

<?php $searchTop = request()->searchTop;?>
<script src="{{ asset('js/io_range-slider.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/css/io_rangeslider.css')}}"> 
<link rel="stylesheet" href="{{ asset('/css/select2.min.css')}}">

<script type="text/javascript">

var $radios = $fmcg('input[name="priceOnRequest"]');
$radios.click(function () {
    $fmcg('#start_from').val(0);
    $fmcg("#product_list").empty(); 
  var $this = $fmcg(this);
  if ($this.data('checked')) {
    
    $fmcg(".price-fi-out").css('display','block');
    $fmcg(".grey_slide").css('display','none');
    $fmcg("#p-end"). attr("readonly", false);
      $fmcg("#p-start"). attr("readonly", false);
    $fmcg("#product_list").empty();
    priceOnRequest=0; 
    product_search();
    this.checked = false;
  }
  else
  {
  if($fmcg('#p-req-both'). prop('checked') ){ 
      $fmcg(".price-fi-out").css('display','block');
      $fmcg(".grey_slide").css('display','none');
      $fmcg("#p-end"). attr("readonly", false);
      $fmcg("#p-start"). attr("readonly", false);
  }
  else{
      $fmcg(".price-fi-out").css('display','none');
      $fmcg(".grey_slide").css('display','block');
      $fmcg("#p-end"). attr("readonly", true);
      $fmcg("#p-start"). attr("readonly", true);
  }
  $fmcg("#product_list").empty();
  priceOnRequest=$fmcg('input[name="priceOnRequest"]:checked').val(); 
  product_search();
  this.checked = true;
  }
  var $otherRadios = $radios.not($this).filter('[name="'
                                               + $this.attr('name') + '"]');
  $otherRadios.prop('checked', false).data('checked', false);
  $this.data('checked', this.checked);
});





var range_value="0-"+'<?=$max_product_price;?>';
var $range = $fmcg(".js-range-slider"),
    range,
    from,
    to;
var type_searchby_text='<?=$type_searchby_text?>';
var priceOnRequest=0;
function range_selfupdate()
{
    var val1 =parseInt($fmcg(".price_from").val());
    var val2 = parseInt($fmcg(".price_to").val());
    if(val1<=val2)
    {
        var max='<?=$max_product_price;?>';
        if(val2>max)
        {
            val1=0;
            val2=max;
            $fmcg(".price_from").val('');
            $fmcg(".price_to").val('');
        } 
        range_value=Math.trunc(val1)+"-"+Math.trunc(val2) ; 
        updateRange(val1,val2);
        $fmcg('#start_from').val(0);
        $fmcg("#product_list").empty();
        //product_search(); 
                  
    }
    else if(val1>val2)
    {
     var val2='<?=$max_product_price;?>';
     $fmcg(".price_from").val('');
     $fmcg(".price_to").val('');
      updateRange(0,val2);
    }

}
var updateRange = function (val1,val2) {
    range.update({
        from: val1,
        to: val2
    });
}

$fmcg(document).ready(function () {
  var updateValues = function () {
    $from.prop("value", from);
    $to.prop("value", to);
};

$range.ionRangeSlider({
    onFinish: function (data) {

      range_value=Math.trunc(data.from)+"-"+Math.trunc(data.to) ; 
      $fmcg(".price_from").val(data.from);
      $fmcg(".price_to").val(data.to);
      $fmcg('#start_from').val(0);
      $fmcg("#product_list").empty();
      //product_search();   
  }
});
 range = $range.data("ionRangeSlider"); 

  $fmcg("#loadmore").hide(); 
  var route = "{{route('available.countries')}}";
  $fmcg('#available_countries').select2({
     placeholder: 'Select Available Countries',

    escapeMarkup: function(markup) { 
          return markup;
    },
    templateResult: function(data) {
       
      return data.html;
    },
    templateSelection: function(data) {
      

      if (data && !data.selected) 
      return data.text;
    },
    ajax: {
      url: route,
      dataType: 'json',
      delay: 250,
      processResults: function(data) {
        return {
          results: $fmcg.map(data, function(item) {
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
  }).on("change", function (e) {
     $fmcg("#selected_country_id").val($fmcg(this).val());
     $fmcg("#product_list").empty();
     $fmcg('#start_from').val(0);
     //product_search();
    });


});
</script>


<script type="text/javascript">
  var brand_id=[];
  var category_id=[];
  var topsubcategory_flag=false;
  $fmcg(document).ready(function(){
            $fmcg("input:checkbox").prop('checked',false);
            product_search();

             $fmcg('#search_keyword').keydown(function (e) {
               if (e.keyCode == 13)
                {
                    $fmcg('#start_from').val(0);
                    $fmcg("#product_list").empty();
                    //product_search();
                }
            });
            $fmcg(".search_keyword_icon").click(function(e){
                $fmcg('#start_from').val(0);
                $fmcg("#product_list").empty();
                //product_search();
            });
            
            
           
            $fmcg(".category_search").keydown(function(e){
               if (e.keyCode == 13)
                {
                  var value=$fmcg(".category_search").val();
                  loadcategory(value);
                }
            });
            $fmcg(".category_search_icon").click(function(e){
              var value=$fmcg(".category_search").val();
               loadcategory(value);
            });

            $fmcg(".brand_search").keydown(function(e){
               if (e.keyCode == 13)
                {
                  var value=$fmcg(".brand_search").val();
                  loadbrand(value);
                }
            });
            $fmcg(".brand_search_icon").click(function(e){
              var value=$fmcg(".brand_search").val();
               loadbrand(value);
            });

            

            $fmcg(document).on("keydown", disableButtonsDown);
});


function loadcategory(val){
   val = val.toLowerCase();
      $fmcg("#ulcatgory li:not(.choosenCategory)").each(function () {
         if ($fmcg(this).text().toLowerCase().search(val) > -1) 
            $fmcg(this).show();
         else 
            $fmcg(this).hide();
         
      });
}

function loadbrand(val){
   val = val.toLowerCase();
      $fmcg("#ulbrands li:not(.choosenBrand)").each(function () {
        if ($fmcg(this).text().toLowerCase().search(val) > -1) 
            $fmcg(this).show();
        else 
            $fmcg(this).hide();
         
      });
}
function disableButtonsDown(e) { 
    if(e.key=='F5'){
        location.reload(true);
       $fmcg("input:checkbox").prop('checked',false);
       category_id=[];
       brand_id=[];
      $fmcg('#start_from').val(0);
      
}
};



function fnPushBrandId(id){
  $fmcg('#start_from').val(0);
  var exists = brand_id.includes(id)

  if (exists) 
  {  
      brand_id= brand_id.filter((c) => { return c !== id })
      $fmcg(".chkbrand[value='" + id + "']").parents('li').removeClass("choosenBrand");
  }
  else
  { 
      brand_id.push(id);
      $fmcg(".chkbrand[value='" + id + "']").parents('li').addClass("choosenBrand");
  }
 $fmcg("#product_list").empty();
 
  //product_search();
}
function fnPushPriceFilter(checkbox){
  priceOnRequest=$fmcg('input[name="priceOnRequest"]:checked').val();
  $fmcg('#start_from').val(0);
  $fmcg("#product_list").empty();
  product_search();
}
function loadmore(){
     product_search();
}

window.addEventListener('unload', function(event) {
    if (event.currentTarget.performance && event.currentTarget.performance.navigation) {
        if (event.currentTarget.performance.navigation.type === PerformanceNavigation.TYPE_RELOAD) {
            // The user refreshed the page
        $fmcg('#start_from').val(0);
        } else if (event.currentTarget.performance.navigation.type === PerformanceNavigation.TYPE_BACK_FORWARD) {
            // The user clicked the browser's back button
        $fmcg('#start_from').val(0);
        }
    } else {
        if (event.clientX < 40 && event.clientY < 0) {
            // The user clicked the browser's back button
        $fmcg('#start_from').val(0);
        } else {
            // The user refreshed the page
        $fmcg('#start_from').val(0);
        }
    }
});

window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
               $fmcg('#start_from').val(0);
    }
});

function fnsubcategory(val,x){
    
     if($fmcg(x).closest('li').hasClass('centralactivecategory'))
    {       
      $fmcg(x).closest('li').removeClass('centralactivecategory');
      $fmcg(".chkcategory[value='" + val + "']").prop('checked', false);
      category_id= category_id.filter((c) => { return c !== val });

    }
    else{
            $fmcg(x).closest('li').addClass('centralactivecategory');
            category_id.push(val);
            $fmcg(".chkcategory[value='" + val + "']").prop('checked', true);
    }
    $fmcg('#start_from').val(0);
    $fmcg("#product_list").empty();
    product_search();
    

}

function fnPushcategoryId(id){
  $fmcg('#start_from').val(0);
  var exists = category_id.includes(id)

  if (exists) 
  {    
      category_id= category_id.filter((c) => { return c !== id })
      $fmcg(".chkcategory[value='" + id + "']").parents('li').removeClass("choosenCategory");
      $fmcg("#subcategory").children("li[value='" + id + "']").removeClass("centralactivecategory");

  }
  else
   { 
       category_id.push(id);
       $fmcg(".chkcategory[value='" + id + "']").parents('li').addClass("choosenCategory");
       $fmcg("#subcategory").children("li[value='" + id + "']").addClass("centralactivecategory");


   }
 
 // console.log(category_id)
   $fmcg("#product_list").empty();
   
  //product_search();
}

   
function product_search(){
  var brand_id_list=brand_id.join();
  var category_id_list=category_id.join();
  var selected_country_id=$fmcg("#selected_country_id").val();
  //$fmcg(".loaderajax").show();
  $fmcg.ajax({
                            type : 'post',
                            url:"{{route('extended_id.search')}}",
                            async: false,
                            data:{
                              _token:'{{ csrf_token() }}',
                              'brand_id':brand_id_list,
                              'category_id':category_id_list,
                              'header_search_key':'{{$search_key}}',
                              'country_id':selected_country_id,
                              'price_range':range_value,
                              'priceOnRequest':priceOnRequest, 
                              'search':$fmcg('#search_keyword').val(),
                              'type_searchby_text':type_searchby_text,
                              'start_from':$fmcg('#start_from').val(),
                              'topcategorysearch':$fmcg('#topcategorysearch').val(),
                              'per_page':60
                            },
                            success:function(data)
                            {
                                  
                                  let dataItems = JSON.parse(data);
                                    

                                  var app_path="<?php echo url('seller-product-detail/')?>";

                                   var initial=0;
                                   dataItems.product_data.map((item) => {
                                    initial++;
                                    productflag=true;
                                   var string= '<div class="col-lg-4 col-12 wow fadeInUp pr-list"><a  href="'+app_path+'/'+item.product_id;
                                   string+='"> <div class="product-thumbnail hovereffect"><div class="pro-img"><img src="'+item.product_thumbnail;
                                   string+='"></div><div class="product-title">';
                                   if (item.product_name.length > 20) 
                                        var prd_name = item.product_name.slice(0, 20) + "...";
                                   else
                                        var prd_name = item.product_name;
                                   <?php if(!Auth::guard('user')->check()) { ?>
                                   
                                   string+='<div class="before-login-pro-dtl"><h3>'+prd_name+' </h3></div>';
                                  <?php } 
                                   elseif(Auth::guard('user')->check()) { ?>
                                   string+='<div class="after-login-pro-dtl"><h3>'+prd_name+' </h3>';
                                   if(item.available_countries!='*****' ) {
                                    string+='<h4>'+item.price+'  </h4>';
                                    string+='<div class="pro-no-loc">';
                                   var country_list='';
                                   var country=item.available_countries;
                                    if(item.available_countries!=null){
                                   country_list='<h5><i class="fa fa-map-marker" aria-hidden="true"></i>'+item.available_countries;
                                   
                                  country_list+='</h5>';
                                    }
                                  string+=country_list+'</div></div>';
                                }
                  
              <?php } ?>        
                  
                  
                  

                                  string+='</div></div></a></div>'
                                   
                                   $fmcg("#product_list").append(string);
                                })
                                var img_path="<?php echo asset('/uploads/defaultImages/no-image.jpg')?>";  

                                var content=$fmcg("#product_list div").length; 
                                if(content==0)
                                      $fmcg("#product_list").append('<img style="width:50%;height:auto;margin:0px auto; display:table;" src="'+img_path+'"/>');

                                var skipcount=parseInt($fmcg("#start_from").val())+ initial ;
                                if(dataItems.count!=skipcount)
                                      $fmcg("#loadmore").show();
                                else
                                      $fmcg("#loadmore").hide();

                                if(dataItems.product_data.length==0)
                                      $fmcg("#loadmore").hide();

                                $fmcg("#start_from").val(skipcount);
                               // $fmcg(".loaderajax").hide();
                                   
                            }
                      });

}

</script>




<script>
function openNav2() {
    document.getElementById("mySidenav2").style.width = "100%";
}

function closeNav2() {
    product_search();
    document.getElementById("mySidenav2").style.width = "0";
}
</script>

            

@endsection
@section('footer_script')




@endsection