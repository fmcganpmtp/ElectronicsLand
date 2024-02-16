<!doctype html>
<html lang="zxx">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="shortcut icon" href="{{ URL::asset('/images/favicon.ico')}}" type="image/x-icon">
		<link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet"> 
		<!-- Bootstrap Min CSS -->
		<link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css')}}">
		<!-- Owl Theme Default Min CSS -->
		<link rel="stylesheet" href="{{ asset('/css/owl.theme.default.min.css')}}">
		<!-- Owl Carousel Min CSS -->
		<link rel="stylesheet" href="{{ asset('/css/owl.carousel.min.css')}}">
		<!-- Meanmenu Min CSS -->
		<link rel="stylesheet" href="{{ asset('/css/meanmenu.min.css')}}">
		<!-- Animate Min CSS -->
		<link rel="stylesheet" href="{{ asset('/css/animate.min.css')}}">
		<link href="{{ asset('/font-awesome-4.6.3/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
		<!-- Style CSS -->
		<link rel="stylesheet" href="{{ asset('/css/extra.css')}}">
		<link rel="stylesheet" href="{{ asset('/css/style.css')}}">
		<!-- Responsive CSS -->
		<link rel="stylesheet" href="{{ asset('/css/responsive.css')}}">
		<link href="{{ asset('/css/animate.css')}}" rel="stylesheet">
		<link href="{{ asset('/css/chat.css')}}" rel="stylesheet">
		<link rel="stylesheet" href="{{asset('/css/style_dataTable.css')}}">
		<script src="{{ asset('js/bootstrap.bundle.min.js')}}"></script>
		<script src="{{ asset('js/jquery1.min.js')}}"></script>
		<script src="{{ asset('js/jquery-ui.js')}}"></script>
		<script src="{{ asset('js/select2.min.js')}}"></script>
		<script src="{{ asset('js/bootstrap3-typeahead.min.js')}}"></script>
		<script src="{{asset('/admin1/js/sweetalert.js')}}"></script>
		<link href="{{ asset('css/jquery-ui1.css')}}" rel="stylesheet" type="text/css" media="all"/>
		<link rel="stylesheet" href="{{asset('/css/cookieconsent.css')}}" media="print" onload="this.media='all'">
		<!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">-->
		<!-- Favicon -->
		<link rel="icon" type="image/png" href="{{ URL::asset('/images/favicon.png')}}">
		<!-- Title -->
		<title>@yield('title')</title>
		<meta name="description" content="@yield('description')">
        <meta property="og:title" content="@yield('title')" />
        <meta property="og:description" content="@yield('description')" />
        <?php $og_logo_url =   asset('images/og_logo.jpg'); ?>
        <meta property="og:image" content="{{ asset('images/og_logo.jpg') }}" />
		<script async src="https://tag.clearbitscripts.com/v1/pk_0d9018251a9f1ab180aed58c2059e241/tags.js" referrerpolicy="strict-origin-when-cross-origin"></script>
  <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-TG7NKKFFF4"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-TG7NKKFFF4');
</script>
	</head>
	<body>
		<div class="loaderajax" style="display: none;">
			<img  src="{{asset('img/loading-gif.gif')}}"/>
		</div>
		<!-- Start Preloader Area -->
		<!-- End Preloader Area -->
		<!-- Start Header Area -->
		<header class="header-area">
			<!-- Start Top Header -->  
			<div class="top-header">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-lg-8">
						</div>
						<div class="col-lg-4">
							<div class="header-right-content"> <a href="mailto:support@fmcgland.com"><i class="fa fa-envelope-o" aria-hidden="true"></i> support@fmcgland.com</a> </div>
						</div>
					</div>
				</div>
			</div>
			<!-- Start Top Header -->
			<!-- Start Middle Header -->
			<div class="middle-header middle-header-style-two">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-lg-3">
							<?php
								$company_logo="";			
								foreach($view_composer_general as $general)
								{
								if( $general->item =='company_logo')
								{ $company_logo=$general->value; }
								}
								if(!empty($company_logo)) $img_url =asset('/assets/uploads/logo/'.$company_logo);
								else $img_url =   asset('images/logo.png'); 
								?>
							<div class="logo"> <a href="{{route('home')}}"> <img src="{{ $img_url }}" alt="Image"> </a> </div>
						</div>
						<div class="col-lg-4">
							
						</div>
						<div class="col-lg-5">
							<?php    
								if(Auth::guard('user')->check()) {  
								 $usertype = Auth::guard('user')->user()->usertype;
									if(!empty(Auth::guard('user')->user()->profile_pic)) 
								                $img_path = asset('/uploads/userImages/').'/'.Auth::guard('user')->user()->profile_pic;
								                else  
								                $img_path = asset('uploads/defaultImages/default_avatar.png'); ?>
							<div class="after-login">
							    @if($view_composer_profile_menu_visible_criteria['flag_blocked_active'] != true)
								@if($usertype !="guest" && $view_composer_profile_menu_visible_criteria['flag2'] == true)
								<ul class="head-right">
									<li class="wish-list wish_show"><a href="{{ route('WishlistItems')}}"><i class="fa fa-heart" aria-hidden="true"></i>
<br>
										<div class="wish-count wishlist_count" @php if($view_composer_wishCount==0) {  echo 'style="display:none;"'; } @endphp>{{ $view_composer_wishCount ?? "" }}
							</div>
							</a></li>
							@if( $view_composer_profile_menu_visible_criteria['network_chat'] == 1)
							     <li class="wish-list"><a href="javascript:void(0)" id="loadChatWindow"><img src="{{ asset('images/chat-ic-03.png') }}"><br>
							@if($view_composer_chact_unreadcnt!=0)
							    <div class="chat-count wish-count">{{ $view_composer_chact_unreadcnt}}</div>
							@else
							    <div class="chat-count wish-count" style="display:none;"></div>
							@endif
							</a></li>
							 @else
                             <li class="wish-list"><a href="javascript:void(0)" onclick="showerrorchat()"><img src="{{ asset('images/chat-ic-03.png') }}"></a>
                            @endif
							</ul>
							@endif
							@endif
							<div class="navbar-option-item navbar-option-language dropdown language-option log-out-C">
								<button class="dropdown-toggle" type="button" id="language2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							
									<div class="pro-logo-out2">
										<div class="log-user  {{Auth::guard('user')->user()->varification_status=='varified' ? 'border-green' : ''}} ">
											<img class="avatar-img" src="{{$img_path}} " alt="{{Auth::User()->name ?? ''}}">
										</div>
										@if(Auth::guard('user')->user()->varification_status=="varified")  
										<div class="active-tick"><i class="fa fa-check" aria-hidden="true"></i></div>
										@endif
									</div>
									<span class="user-name01"> {{ Auth::guard('user')->user()->name ?? '' }}</span> 
									@if(Auth::guard('user')->user()->varification_status!="varified"  )<i class="fa fa-exclamation notific" aria-hidden="true" ></i>@endif
								</button> 
								
									
								<div class="dropdown-menu language-dropdown-menu" aria-labelledby="language2">
								{{--	@if($view_composer_profile_menu_visible_criteria['general_companyDashboard'] == 1 )--}}
								@if(($view_composer_profile_menu_visible_criteria['flag_blocked_active'] != true) && $view_composer_profile_menu_visible_criteria['flag2'])
									<a class="dropdown-item" href="{{route('seller.dashboard')}}"> <i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard</a>
							    @endif
								{{--	@endif  --}}
								{{-- @if($view_composer_profile_menu_visible_criteria['general_companyProfile'] == 1 )--}}
									<a class="dropdown-item" href="{{route('ViewProfileSeller')}}"> <i class="fa fa-user" aria-hidden="true"></i> My profile </a> 
								 {{-- @endif --}}
									@if((Auth::guard('user')->user()->seller_type!='Co-Seller')  && $view_composer_profile_menu_visible_criteria['flag2'] == true)
									<a class="dropdown-item" href="{{route('seller.kyc.approval')}}"> <i class="fa fa-clone" aria-hidden="true"></i> KYC approval @if(Auth::guard('user')->user()->varification_status!="varified" )<i class="fa fa-exclamation notific" aria-hidden="true" ></i>@endif</a>
									@endif
									@if(($view_composer_profile_menu_visible_criteria['flag_blocked_active'] != true) && $view_composer_profile_menu_visible_criteria['flag2'])
									{{-- @if($view_composer_profile_menu_visible_criteria['network_expand'] == 1)--}}
									 <a class="dropdown-item" href="{{route('user.mynetwork')}}"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i>My Networks </a> 
									{{-- @endif --}}
									@if( $view_composer_profile_menu_visible_criteria['general_companyDashboard'] == 1 && Auth::guard('user')->user()->seller_type!='Co-Seller' ) 
								<!--	<a class="dropdown-item" href="{{route('user.listcosellers')}}"> <i class="fa fa-toggle-on" aria-hidden="true"></i> Manage Co-Users </a> -->
									@endif
									
								{{--	@if($view_composer_profile_menu_visible_criteria['prd_menu_visble'] == 1 || $view_composer_profile_menu_visible_criteria['active_product_count'] >0)--}}
									<a class="dropdown-item" href="{{route('seller.products')}}"> <i class="fa fa-plus-square" aria-hidden="true"></i> Product </a>
								{{--	@endif --}}
									
								{{--	@if($view_composer_profile_menu_visible_criteria['market_productRequests'] == 1 || $view_composer_profile_menu_visible_criteria['market_createProductRequests'] ==1) --}}
									<a class="dropdown-item" href="{{ route('Product.Requests')}}"> <i class="fa fa-tasks" aria-hidden="true"></i> Product requests </a>
								{{--	@endif--}}
									@endif
									@if( Auth::guard('user')->user()->seller_type!='Co-Seller' ) 
									<a class="dropdown-item" href="{{ route('subscription.details')}}"> <i class="fa fa-tasks" aria-hidden="true"></i> Subscription details </a>
									@endif
									<a class="dropdown-item" href="{{route('logout')}}"> <i class="fa fa-sign-out" aria-hidden="true"></i>Log out </a> 
								</div>
								
								
							</div>
						</div>
						<?php } else { ?> 
						<ul class="head-right">
							<li><a href="{{route('user-login')}}"><i class="fa fa-user" aria-hidden="true"></i><br><b>Sign in</b></a></li>
							<li><a href="{{ route('user-register') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <br> <b>Register</b></a></li>
						</ul>
						<?php } ?>          
					</div>
					
					
				</div>
			</div>
			</div>
			<!-- Start Middle Header -->
			<!-- Start Navbar Area -->
			<div class="navbar-area navbar-area-style-two">
				<div class="mobile-responsive-nav">
					<div class="container">
						<div class="mobile-responsive-menu">
							<div id='cssmenu'>
								<div id="head-mobile"></div>
								<div class="button menu-button"></div>
								<ul id="mobilemenu" class="ui-menu"></ul>
							</div>
							<div class="logo"> <a href="{{route('home')}}"><img src="{{URL::asset('images/logo.png')}}"> </a> </div>
						</div>
					</div>
				</div>
				<div class="desktop-nav">
				    
				    
					<div class="container">
					<div class="row">
					
					<div class="col-lg-3">
						<nav class="navbar navbar-expand-md navbar-light">
							<div class="navbar-category">
								<button type="button" id="categoryButton2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="ri-menu-line"></i> ALL CATEGORIES <i class="arrow-down ri-arrow-down-s-line"></i> </button>
								<div class="new-cat-menu">
								<ul id="desktopmenu" class="ui-menu"></ul>  
								</div>
							</div>
							
								</nav>
								
								</div><!-->
							
							<!--menu.css-->
							<style>
							.ui-menu{ min-width:320px;}
							    .ui-menu-item{ height:auto;}
							    .ui-menu-item a{     padding: 6px!important;
    font-size: 14px; display:block;}
							    .navbar-category button { padding: 8px 24px;}
							    
							    
							      .ui-menu .ui-menu-item-wrapper{margin:0px 0px!important;}
							    
							   .ui-menu .ui-menu-item-wrapper:hover .ui-icon{display:block!important;}
							   
					
    
.desktop-nav .navbar ul { top: 0!important; height: auto %!important;}

.ui-icon{background:none!important; right:10px; 


  width: 5px;
  height: 5px;
  border: 1px solid #333;
  border-left: 0;
  border-top: 0; transform: rotate(315deg); 
}
	.ui-icon {
    width: 5px!important;
    height: 5px!important;
    margin-right: 5px!important;
}	


.header-area{position:relative; z-index:20;}
body{position:relative;}



.ui-front li:first-child a{font-weight:bold;}

.menu-backdrop2{width:100%; height:100%!important;top:0; bottom:0;right:0;left:0;  position:absolute; z-index:5; display:none; margin:0px auto;}


.desktop-nav .navbar ul ul li{ border:none;}
}

.desktop-nav .navbar ul ul ul li{ border:none;}
.desktop-nav .navbar ul ul ul li{ border:none;}

.desktop-nav .navbar ul ul{border:none;     border-right: 1px solid #c5c5c5; border-left: 1px solid #c5c5c5;}

.desktop-nav ul{box-shadow: 12px 14px 15px -3px rgba(0,0,0,0.1);padding-left:10px!important;}


#desktopmenu{
   /* height:780px!important;*/
    height:auto !important;
    overflow-y:auto;}
#desktopmenu ul ul{overflow-y:auto;}

.ui-menu-item { border:none!important;
}

.ui-menu .ui-menu-icon {

    right: 10px!important;
}

.desktop-nav .navbar  ul{-webkit-transition: all .6s ease-in-out;transition: all .6s ease-in-out;}

							</style>
							
							
				
			
			<style>
/* width */
.desktop-nav ::-webkit-scrollbar {
  width: 8px;
}

/* Track */
.desktop-nav ::-webkit-scrollbar-track {
  background: #f1f1f1; 
}
 
/* Handle */
.desktop-nav ::-webkit-scrollbar-thumb {
  background: #e0e0e0; 
}

/* Handle on hover */
.desktop-nav ::-webkit-scrollbar-thumb:hover {

  background: #cecece; 
}


.image {
  width: 8%;
  float: left;
  padding: 10px;
}

.label_search {
  width: 90%; float:left;}
  
  

</style>				
							
							
							
							
							
		<div class="col-lg-7">
		
		<div class=" middle-header search-sec-new">
		
			<?php
							$search_key_value = Request::get('search_key');
							  if(!empty($search_key))
									$search_key_value = $search_key;
							   if(!empty($category_Name_fr_slug))
									$search_key_value = $category_Name_fr_slug;
									?>
							<form class="search-box" action="{{route('headsearch')}}"> 
								<input type="text"  name="search" autocomplete="off" id="search" class="form-control input-lg  {{ $errors->has('search') ? ' is-invalid' : '' }} hd_srch" value="{{$search_key_value}}" placeholder="Search"  >
								@if ($errors->has('search'))
								<span class="invalid-feedback" role="alert">
								<strong>{{ $errors->first('search') }}</strong>
								</span>
								@endif
								<button type="submit" class="search-btn hd_srch_btn"> <i class="ri-search-line"></i> Search</button>
							</form>
							
							
							
		</div>					
							
							
					</div>		
							
							
							
				<!--			
							<div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">-->
							
								<!-- <ul class="navbar-nav">  
									@if(!empty($view_composer_TopmenuCats_visible))
									@foreach($view_composer_TopmenuCats_visible as $visible_item)
									<li class="nav-item"><a href="{{route('Product.Listing',['search_key'=>$visible_item->category->slug])}}" class="nav-link">{{$visible_item->category->name ?? ""}}</a></li>
									@endforeach
									@endif 
									@if(!empty($view_top_content))
									@foreach($view_top_content as $item)
									<li class="nav-item"><a href="{{url($item->seo_url)}}" class="nav-link">{{$item->page}}</a></li>
									@endforeach
									@endif 
									</ul>-->
									
						<div class="col-lg-2">			
								<div class="others-options">
									<div class="top-social">
										<ul>
											@if(!empty($view_composer_socialIcons))
											@foreach($view_composer_socialIcons as $view_composer_socialIcon)
											@if(($view_composer_socialIcon->type=="image"))
											<li><a href="{{$view_composer_socialIcon->link}}" target="_blank"><img style="width:30px;" src="{{ URL::asset('/assets/uploads/socialmedia/'.$view_composer_socialIcon->icon)}}" >
												</a>
											</li>
											@else
											<li><a href="{{$view_composer_socialIcon->link}}" target="_blank"><?php  echo $view_composer_socialIcon->icon; ?>
												</a>
											</li>
											@endif
											@endforeach
											@endif
										</ul>
									</div>
								</div>
								
								</div><!--2-->
							</div>
					
					</div>
				</div>
			</div>
			<!-- End Navbar Area -->
<script  type="text/javascript">
    var url_subpath = '{{url('product-listing')}}/';
    var $fmcg = $.noConflict();
    $fmcg(function() {
        
    

    var getMenuItem = function(itemData) {
    var item = $fmcg("<li>")
      .append($fmcg("<a>", {
          href:url_subpath+itemData.link,
          html: itemData.name
        }));
    if (itemData.sub.length>0) {
      var subList = $fmcg("<ul>");
      var itemhead = $fmcg("<li>")
      .append($fmcg("<a>", {
          href: '#',
          html: itemData.name
        }));
      subList.append(itemhead);
      $fmcg.each(itemData.sub, function() {
        subList.append(getMenuItem(this));
      });
      item.append(subList);
    }
    return item;
  };

var getMenuItem1 = function(itemData) {
    var item = $fmcg("<li>")
      .append($fmcg("<a>", {
          href:url_subpath+itemData.link,
          html: itemData.name
        }));
    if (itemData.sub.length>0) {
      var subList = $fmcg("<ul>");
      $fmcg.each(itemData.sub, function() {
        subList.append(getMenuItem(this));
      });
      item.append(subList);
    }
    return item;
  };

$fmcg.ajax({
               url: "{{ url('buildmenustructure') }}",
               type: "get",
               async:true,
               cache: false,
               dataType: 'json',
               success: function(menu_structure){
                    
                      var menu_structure_dt = {menu: menu_structure};
                      var desktopmenu = $fmcg("#desktopmenu");
                      $fmcg.each(menu_structure_dt.menu, function() {
                        desktopmenu.append(getMenuItem(this));
                      });
                      desktopmenu.menu();
                      var mobilemenu = $fmcg("#mobilemenu");
                      $fmcg.each(menu_structure_dt.menu, function() {
                        mobilemenu.append(getMenuItem1(this));
                      });
                      mobilemenu.menu();
                  
        } ,
             error: function(XMLHttpRequest, textStatus, errorThrown) { 
               
             }  
   
           })  ;

});


</script>

			
		</header>
		
		
		
