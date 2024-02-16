@extends('layouts.template_mobile')
@section('title', 'Product Request')
@section('content')

<!-- Start Page Title Area -->
<div class="page-title-area">
  <div class="container">
    <div class="page-title-content">
     
    </div>
  </div>
</div>

<style>
    
    .pr-req .default-btn{padding:8px 10px;    padding: 8px 10px 11px;margin-right:2px;
}
 .pr-req .pro-no-loc{margin-bottom:8px;}

.mar-btm-20{margin-bottom:25px;}
.days-remain{margin-top:25px; font-style:italic;opacity:0.5;}

 .autocomplete{box-shadow:none!important;border: 1px solid #ced4da!important; border-radius:0px!important;}

.select2-container {min-height:53px!important;box-shadow:none!important;}
.select2-container--default .select2-selection--multiple{box-shadow:none!important;    
}

.select2-search__field{
    background: transparent;
    border: none;
    outline: 0;
    box-shadow: none;
    font-size: 13px!important;  
    margin-left: 15px!important;  
    margin-top: 18px!important;  ;
 
}

.pr-req .mar-btm-20 .default-btn{width:48%; line-height:17px; font-size:13px; text-transform:uppercase;    padding: 10px 10px 11px;
}

.no-brdd{border:none;}

.filter-bg-out{background:#f1f1f1;padding:10px;}

.select2-container .select2-search--inline .select2-search__field{font-family: 'Poppins', sans-serif!important;}

.filter-bg-out input{background:#fff!important;}

.filter-bg-out .select2-container{background:#fff!important;}
.filter-bg-out .form-control{background:#fff!important;}

.product-thumbnail .default-btn i{top:0; margin-right:8px;}

.pr-req .mar-btm-20 .default-btn { width:100%;}
.col-lg-12 .default-btn{    padding: 20px 10px 18px;
}
.pr-req .mar-btm-20 .default-btn br{display:none;}

.filter-bg-out .form-group{margin-bottom:10px;}
 .filter-bg-out .select2-container{margin-bottom:20px;}
.filter-bg-out .select2-search__field{margin-top:0px!important;}
.form-control{color:#333;}

.req-msg-btn{display:flex;margin:10px auto; overflow:hidden;}

.req-msg-btn .default-btn{width:48%;float:left;}

.pr-req .mar-btm-20 .default-btn{margin-bottom:5px!important;}

.pr-req .mar-btm-20 .default-btn {


    padding: 17px 10px 16px;
}
.sg-list button{top:6px;}
.filter-bg-out .select2-container {
    margin-bottom: 0;
}

.select2-container {
    min-height: 50px!important;
}
.select2-container--default .select2-selection--multiple {margin-top:-3px!important;}

.product-title h3{margin-bottom:10px;}

</style>

<!-- Start Product Area -->
@if($view_composer_profile_menu_visible_criteria['market_productRequests'] == 1)  
<section class="products-area ptb-54 innerpageC pr-req">  
  <div class="container">
      <div class="row mar-btm-20">
          
          
          
          
          <div class="col-lg-12">
              
              <div class="filter-bg-out"> 
              
              <div class="row">
              <div class="succes-outer">@if(Session::has('product_message')) <div class="alert alert-success">{{Session::get('product_message') }}</div> @endif    </div>
      <div class="col-lg-3">
            
            <div class="form-group" >
              <select type="text" placeholder="Select Category" name="category_id_l" id='category_id_l' class="form-control{{ $errors->has('product_dimension') ? ' is-invalid' : '' }}" >
             <option value="">Select Category</option>
              @foreach($prd_req_cat_ids as $data)
                    <option value="{{$data['id']}}"  {{ ( $data['id'] == request()->cat_id) ? 'selected' : '' }}>{{$data['name']}}</option>
                    @endforeach
              </select>
              <input type="hidden" id="main_category_id" value="" name="main_category_id">
            </div></div>
      <div class="col-lg-3">
            
            <div class="autocomplete form-group sg-list" >
      
              <select type="text" placeholder="Select Country" name="available_countries[]" id='available_countries' class="form-control{{ $errors->has('product_dimension') ? ' is-invalid' : '' }}" multiple="multiple">
              </select>
              <input type="hidden" id="selected_country_id">
              <input type="hidden" id="start_from" value="0">
              <!--<button type="button"><i class="fa fa-search" aria-hidden="true"></i></button>-->
            </div></div>
      <div class="col-lg-3">
          
                <div class="autocomplete form-group sg-list" >
                  <div class="srch-left-31">
                <input type="text" name="keyword" id='search_keyword' class="form-control no-brdd"  placeholder="Search by Name">
                <input type="hidden" name="topcategorysearch" id='topcategorysearch' value="{{$search_key}}">
                        <button type="submit" class="search_keyword_icon"><i class="fa fa-search" aria-hidden="true"></button></i>
                </div>
         </div></div>
      <div class="col-lg-3">
          @if($view_composer_profile_menu_visible_criteria['market_createProductRequests'] == 1)
          <a href="{{route('add.productrequest')}}" class="default-btn">Create Product <br>Request</a>
          @else
          <a href="javascript:void(0)" onclick="showerrorPrdReq()" class="default-btn crt-rwq">Create Product request</a>
         @endif
          @if(( $view_composer_profile_menu_visible_criteria['market_createProductRequests'] == 1)	)
           @if($view_composer_profile_menu_visible_criteria['market_productRequests'] == 1 )
            <a href="{{route('MyProduct.Requests')}}" class="default-btn">My Product<br> Request</a>
           @else
            <a href="javascript:void(0)" onclick="showerrorMyPrdReq()" class="default-btn crt-rwq">My Product<br> Request</a>
         @endif
         @endif
      </div>
      
      </div><!--row-->
              </div><!--filter-bg-out-->
              
              </div><!--12-->
      
      
      </div>
      
    <div class="row">
      <div class="col-lg-3">
        <div class="widget-sidebar mr-15">
         
        
             @if(count($left_menu_list)>0)
                <div class="sidebar-widget borddr-bx1 categories"> 
                    
                    
                     <h3>Sub Categories</h3>
                  <ul id="ulcatgory">
                    
                    @foreach($left_menu_list as $data)
                    <li><input type="checkbox" class="chkcategory"  onclick="fnPushcategoryId({{$data['id']}})" value="{{$data['id']}}"> &nbsp;&nbsp;{{$data['name']}}</li>
                    @endforeach
                  </ul>
                </div>
               @endif

            
       
        </div>
      </div>


      <div class="col-lg-9 col-12">

        
<div class="row">
    
</div>
        <div class="row" id="product_list"> </div>  
        <div class="default-btn load-btn" style="display: none;" id="loadmore" onclick="loadmore()">Load More...</div> 
      </div>
    </div>
  </div>
</section>
@else
 <div class="container">
      <div class="row mar-btm-20">
          <div class="col-lg-12">
              
              <div class="no-prv-pro-req">
                  
                 <div class="crt-ic"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></div> 
<h4 class="no=prev" >Sorry! You have no privilage to view product requets as per the current package. Would you like to upgrade package? <a href='{{route('package.listing')}}'>Upgrade</a></h4>
         @if($view_composer_profile_menu_visible_criteria['market_createProductRequests'] == 1)
          <a href="{{route('add.productrequest')}}" class="default-btn">Create Product Request</a>
          <a href="{{route('MyProduct.Requests')}}" class="default-btn">My Product Request</a>
           @else
          <a href="javascript:void(0)" onclick="showerrorPrdReq()" class="default-btn crt-rwq">Create Product request</a>
         @endif
          
            </div>  
  </div>  
  </div>
  </div>
@endif 
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



$fmcg(document).ready(function () {
    
  var updateValues = function () {
    $from.prop("value", from);
    $to.prop("value", to);
};

 

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
     product_search();
    });


});
</script>


<script type="text/javascript">
  var brand_id=[];
  var category_id=[];
  var topsubcategory_flag=false;
  $fmcg(document).ready(function(){
            product_search();

             $fmcg('#search_keyword').keydown(function (e) {
               if (e.keyCode == 13)
                {
                    $fmcg('#start_from').val(0);
                    $fmcg("#product_list").empty();
                    product_search();
                }
            });
            $fmcg(".search_keyword_icon").click(function(e){
                $fmcg('#start_from').val(0);
                $fmcg("#product_list").empty();
                product_search();
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


function disableButtonsDown(e) { 
    if(e.key=='F5'){
        location.reload(true);
       $fmcg("input:checkbox").prop('checked',false);
       category_id=[];
       brand_id=[];
      $fmcg('#start_from').val(0);
      
}
};




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

window.addEventListener('popstate', function(event) {
                $fmcg('#start_from').val(0);

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
      $fmcg(".chkcategory[value='" + id + "']").parents('li').removeClass("choosenCategory");
      $fmcg("#subcategory").children("li[value='" + id + "']").removeClass("centralactivecategory");
      category_id= category_id.filter((c) => { return c !== id });
  }
  else
   { 
       $fmcg(".chkcategory[value='" + id + "']").parents('li').addClass("choosenCategory");
       $fmcg("#subcategory").children("li[value='" + id + "']").addClass("centralactivecategory");
       category_id.push(id);
   }
 
  
   $fmcg("#product_list").empty();
   
  product_search();
}

function product_search(){
  var brand_id_list=brand_id.join();
  var category_id_list=category_id.join();
  var selected_country_id=$fmcg("#selected_country_id").val(); 
  var category_id_l=$fmcg("#category_id_l").val(); 
  //$fmcg(".loaderajax").show();
  $fmcg.ajax({
                            type : 'post',
                            url:"{{route('ProuctRquestSearch')}}",
                            async: false,
                            data:{
                              _token:'{{ csrf_token() }}',
                              'category_id':category_id_list,
                              'header_search_key':'{{$search_key}}',
                              'country_id':selected_country_id,
                              'search':$fmcg('#search_keyword').val(),
                              'category_id_l':category_id_l,
                              
                              'start_from':$fmcg('#start_from').val(),
                              'topcategorysearch':$fmcg('#topcategorysearch').val(),
                              'per_page':60
                            },
                            success:function(data)
                            {
                                  
                                  let dataItems = JSON.parse(data);
                                    

                                 

                                   var initial=0;
                                   dataItems.product_data.map((item) => {
                                    initial++;
                                    productflag=true;
                                    var app_path="<?php echo url('view-seller-profile/')?>"; 
                                    
                                   var string= '<div class="col-lg-4 col-12 wow fadeInUp pr-list"><a href="'+app_path+'/'+item.parent_id+'" target="_blank"><div class="product-thumbnail hovereffect"><div class="days-remain">'+item.days_remaining+' Days Remaining</div><div class="pro-img"><a href="'+app_path+'/'+item.parent_id+'" target="_blank"><img src="'+item.product_thumbnail;
                                   string+='"></a></div><div class="product-title"><h5 class="looking-for"><b>We are Looking for:</b></h5> <h3>';
                                   if (item.product_name.length > 25) 
                                        var prd_name = item.product_name.slice(0, 25) + "...";
                                   else
                                        var prd_name = item.product_name;
                                   string+=prd_name+' </h3>';
                                    @if(Auth::guard('user')->check())
                                   if(item.company_name!=''){
                                   string+='<h3>'+item.company_name+'</h3><h5>';
                                   }
                                   @endif
                                   /*string+='<div class="pro-no-loc"><h5>';
                                   var country_list='';
                                   var countries= item.country;
                                   if(countries!=''){
			                        for(var index = 0; index < countries.length; index++) {
                                    country_list+='&nbsp;&nbsp;<i class="fa fa-map-marker" aria-hidden="true"></i>'+countries[index]+',';
                                   }
                                   country_list=country_list.slice(0,-1); 
                                   }*/
                                   
                                   //string+=country_list+'</h5></div>';
                                   if(item.country!='' && item.country != null){
                                   string+='&nbsp;&nbsp;<i class="fa fa-map-marker" aria-hidden="true"></i>'+item.country+'';
                                   }
                                   
                                   string+='</h5></div><div class="req-btns">';
                                    @if( $view_composer_profile_menu_visible_criteria['general_companyProfile'] == 1)
                                   string+='<a  href="'+app_path+'/'+item.parent_id;
                                   string+=' " class="default-btn"> <i class="fa fa-user" aria-hidden="true"></i>Seller profile</a>';
                                   @endif
                                   @if( $view_composer_profile_menu_visible_criteria['network_chat'] == 1)
                                   string+='<a href="javascript:void(0)" class="default-btn" onclick="EnableChatRequest('+item.user_id+')" ><i class="fa fa-envelope-o" aria-hidden="true"></i>Message </a>';
                                   @endif
                                   string+='</div>';
                                   string+='<div class="product-ms-over">';
                                   
			                       string+='<div class="pr-hover-border-bx"><div class="req-contet">';
			                       if (item.product_name.length > 200) 
                                        var prd_name = item.product_name.slice(0, 200) + "...";
                                   else
                                        var prd_name = item.product_name;
				                   string+='<h4>We are looking for:</h4>';
				                   string+='<div class="ms-over-txt">';
				                   
					               string+='<p>'+prd_name+'</p></div>';
					               if(item.quantity!=''){
				                   string+='<h5>Quantity : '+item.quantity+'</h5>';
					               } if(item.language!=''){
				                   string+='<h6>language : '+item.language+'</h6>';
					               }
			                       string+='</div></div></div>';
			
                                   string+='</div></div></div>';
                                   
                                  
                                   
                                   
                                   
                                   
                                   
                                   
                                   
                                  // string+='</div>';
                                   
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
/*$fmcg('#category_id_l').on("change", function (e) { 
     $fmcg("#main_category_id").val($fmcg(this).val());
     $fmcg("#product_list").empty();
     $fmcg('#start_from').val(0);
     product_search();
    });*/
    
   $fmcg('#category_id_l').change(function(){
         $fmcg("#main_category_id").val($fmcg(this).val());
         let cat_id = $fmcg(this).val();
         var app_path="<?php echo url('product-requests/')?>";
         if(cat_id === '')
         window.location.href = app_path ;
         else
         window.location.href = app_path + '/?cat_id=' + cat_id;

    }); 
    
    
</script>

            

@endsection
@section('footer_script')




@endsection