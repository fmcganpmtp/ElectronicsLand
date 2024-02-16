<!DOCTYPE html>

<!-- Breadcrumb-->
<html lang="en">
<head>
<base href="{{asset('/')}}">
<!--<base href="./">-->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

<title>@yield('title')</title>
<script src="{{asset('/admin1/js/jquery.min.js')}}"></script>

<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" ></script> -->
<link rel="apple-touch-icon" sizes="57x57" href="{{asset('/admin1/assets/favicon/apple-icon-57x57.png')}}">
<link rel="apple-touch-icon" sizes="60x60" href="{{asset('/admin1/assets/favicon/apple-icon-60x60.png')}}">
<link rel="apple-touch-icon" sizes="72x72" href="{{asset('/admin1/assets/favicon/apple-icon-72x72.png')}}">
<link rel="apple-touch-icon" sizes="76x76" href="{{asset('/admin1/assets/favicon/apple-icon-76x76.png')}}">
<link rel="apple-touch-icon" sizes="114x114" href="{{asset('/admin1/assets/favicon/apple-icon-114x114.png')}}">
<link rel="apple-touch-icon" sizes="120x120" href="{{asset('/admin1/assets/favicon/apple-icon-120x120.png')}}">
<link rel="apple-touch-icon" sizes="144x144" href="{{asset('/admin1/assets/favicon/apple-icon-144x144.png')}}">
<link rel="apple-touch-icon" sizes="152x152" href="{{asset('/admin1/assets/favicon/apple-icon-152x152.png')}}">
<link rel="apple-touch-icon" sizes="180x180" href="{{asset('/admin1/assets/favicon/apple-icon-180x180.png')}}">
<link rel="icon" type="image/png" sizes="192x192" href="{{asset('/admin1/assets/favicon/android-icon-192x192.png')}}">
<link rel="icon" type="image/png" sizes="32x32" href="{{asset('/admin1/assets/favicon/favicon-32x32.png')}}">
<link rel="icon" type="image/png" sizes="96x96" href="{{asset('/admin1/assets/favicon/favicon-96x96.png')}}">
<link rel="icon" type="image/png" sizes="16x16" href="{{asset('/admin1/assets/favicon/favicon-16x16.png')}}">
<link rel="manifest" href="{{asset('/admin1/assets/favicon/manifest.json')}}">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="{{asset('/admin1/assets/favicon/ms-icon-144x144.png')}}">
<link rel="icon" type="image/png" sizes="16x16" href="{{asset('/admin1/assets/favicon/favicon.png')}}">
<!-- <link rel="stylesheet" href="https://unpkg.com/@coreui/coreui/dist/css/coreui.min.css">
<script src="https://unpkg.com/@coreui/coreui/dist/js/coreui.bundle.min.js"></script> -->

<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="{{asset('/admin1/assets/favicon/ms-icon-144x144.png')}}">
<link rel="icon" type="image/png" sizes="16x16" href="{{asset('/admin1/assets/favicon/favicon.png')}}">

<meta name="theme-color" content="#ffffff">
<!-- Vendors styles-->
<link rel="stylesheet" href="{{asset('/admin1/vendors/simplebar/css/simplebar.css')}}">
<link rel="stylesheet" href="{{asset('/admin1/css/additional_style.css')}}">
<link rel="stylesheet" href="{{asset('/admin1/css/vendors/simplebar.css')}}">
<link href="{{asset('/admin1/css/image-zoom.css')}}" rel="stylesheet">
<!-- Main styles for this application-->
<link href="{{asset('/admin1/css/style_dataTable.css')}}" rel="stylesheet">
<link href="{{asset('/admin1/css/style.css')}}" rel="stylesheet">
<!--<link href="{{asset('/admin1/css/style_dataTable.css')}}" rel="stylesheet">-->
<!-- We use those styles to show code examples, you should remove them in your application.-->

<link rel="stylesheet" href="{{asset('/admin1/css/prism.css')}}">

<link href="{{asset('/admin1/css/examples.css')}}" rel="stylesheet">
<!-- Global site tag (gtag.js) - Google Analytics-->
<script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-118965717-3"></script>
<script>
      window.dataLayer = window.dataLayer || [];

      function gtag() {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());
      // Shared ID
      gtag('config', 'UA-118965717-3');
      // Bootstrap ID
      gtag('config', 'UA-118965717-5');
    </script>
</head>
<script src="https://use.fontawesome.com/6b4068de03.js"></script>

<body>
    <div class="loaderajax" style="display: none;">
                          <img  src="{{asset('img/Loading_icon.gif')}}"/></div>
<div class="sidebar sidebar-dark sidebar-fixed " id="sidebar">
  
  <ul class="sidebar-nav" data-coreui="navigation " data-simplebar="">
    
 
<li class="nav-item active"><a class="nav-link" href="{{route('admin.dashboard')}}"><i class="fa fa-tachometer" aria-hidden="true"></i>Dashboard</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('list-users')}}"><i class="icon  cil-address-book"></i>Admin Users</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('list.permissions')}}"><i class="icon cil-list-rich"></i>Admin Permissions</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('list.admin.roles')}}"><i class="icon icon-2xl cil-playlist-add"></i>Admin User Roles</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('list.company.type')}}"><i class="fa fa-bed" aria-hidden="true"></i>Company Types</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('list.ProductRequest')}}"><i class="fa fa-paper-plane" aria-hidden="true"></i>Product Request</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('list.currency')}}"><i class="fa fa-money" aria-hidden="true"></i>Currencies</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('category.list')}}"><i class="fa fa-clone" aria-hidden="true"></i>Categories</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('admin.kyclist')}}"><i class="fa fa-check-square-o" aria-hidden="true"></i>KYC Verification</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('admin.sellerslist')}}"><i class="fa fa-sellsy"></i>Sellers List</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('admin.buyerslist')}}"><i class="fa fa-sort"></i>Buyers List</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('admin.guestlist')}}"><i class="fa fa-sort"></i>Guest List</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('admin.orderlist')}}"><i class="fa fa-pie-chart"></i>Orders History</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('admin.advertisementlist')}}"><i class="fa fa-picture-o"></i>Advertisement Listing</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('list.packges')}}"><i class="fa fa-address-card" aria-hidden="true"></i>Subscription Package Listing</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('subscription.users')}}"><i class="fa fa-newspaper-o" aria-hidden="true"></i>Subscription Users List</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('list-products')}}"><i class="fa fa-sort-amount-desc" aria-hidden="true"></i>Products</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('list-Sellerproducts')}}"><i class="fa fa-sort-amount-desc" aria-hidden="true"></i>Seller Products</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('admin.listVendorProduct')}}"><i class="fa fa-snowflake-o" aria-hidden="true"></i>Product Create Request</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('admin.profilerequest')}}"><i class="fa fa-address-card" aria-hidden="true"></i>Profile Delete Request</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('admin.brands')}}"><i class="fa fa-database" aria-hidden="true"></i>Brands</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('admin.testimonials')}}"><i class="fa fa-file-text-o" aria-hidden="true"></i>Testimonials</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('admin.newsletters')}}"><i class="fa fa-cogs" aria-hidden="true"></i>Newsletter Subscriptions</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('admin.promotionalnewsletters')}}"><i class="fa fa-cogs" aria-hidden="true"></i>Promotional Newsletter List</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('admin.sellerinsight')}}"><i class="fa fa-book" aria-hidden="true"></i></i>Sellers Insight</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('admin.buyerinsightreport')}}"><i class="fa fa-book" aria-hidden="true"></i></i>Buyers Insight</a></li>
<!--<li class="nav-item"><a class="nav-link" href="{{route('stripe.status')}}"><i class="fa fa-cc-stripe" aria-hidden="true"></i></i>Stripe Status</a></li>-->
<li class="nav-item"><a class="nav-link" href="{{route('admin.socialmedia')}}"><i class="fa fa-bell-o" aria-hidden="true"></i></i>Social Media</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('admin.settings')}}"><i class="fa fa-cogs" aria-hidden="true"></i>General Settings</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('admin.sliders')}}"><i class="fa fa-camera" aria-hidden="true"></i></i>Sliders</a></li>
<li class="nav-item"><a class="nav-link" href="{{route('admin.contentpages')}}"><i class="fa fa-book" aria-hidden="true"></i></i>Content Pages</a></li>
<!--<li class="nav-item"><a class="nav-link" href="{{route('admin.listfrontendmenu')}}"><i class="fa fa-book" aria-hidden="true"></i></i>Front End Menu</a></li> -->
<li class="nav-item"><a class="nav-link" href="{{route('admin.listtopcategory')}}"><i class="fa fa-book" aria-hidden="true"></i></i>Top Categories</a></li>




 
</div>
<div class="wrapper d-flex flex-column min-vh-100 bg-light">
  <header class="header header-sticky mb-4">
    <div class="container-fluid">
      <button class="header-toggler px-md-0 me-md-3" type="button" onClick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
      <svg class="icon icon-lg">
        <use xlink:href="{{asset('/admin1/vendors/@coreui/icons/svg/free.svg#cil-menu')}}"></use>
      </svg>
      </button>
      <a class="header-brand d-md-none" href="#"> </a>
      <ul class="header-nav d-none d-md-flex">
        <li class="nav-item"><a class="nav-link" href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="{{route('admin.profile')}}">Profile</a></li>
      </ul>
      <ul class="header-nav ms-auto">
      </ul>
      <ul class="header-nav ms-3">
        <li class="nav-item dropdown">
          <a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
          <span class="user-Name">{{ Auth::guard('admin')->user()->name}}<br/>
          <span class="job-title-05">{{ Auth::guard('admin')->user()->job_title}}</span></span>
      
          <div class="avatar avatar-md"><img class="avatar-img" src="{{ Auth::guard('admin')->user()->profile_pic ? asset('/uploads/userImages/').'/'.Auth::guard('admin')->user()->profile_pic : asset('uploads/defaultImages/default_avatar.png') }} " alt="user@email.com"></div>
          </a>
          <div class="dropdown-menu dropdown-menu-end pt-0">
            <div class="dropdown-header bg-light py-2">
              <div class="fw-semibold">Account</div>
            </div>
            <a class="dropdown-item" href="{{route('admin.profile')}}">
            <i class="fa fa-user-o"></i>
            <!-- <svg class="icon me-2">
              <use xlink:href="{{asset('/admin1/vendors/@coreui/icons/svg/free.svg#cil-account-logout')}}"></use>
            </svg> -->
           &nbsp; Profile</a> <a class="dropdown-item" href="{{route('admin.logout')}}">
            <svg class="icon me-2">
              <use xlink:href="{{asset('/admin1/vendors/@coreui/icons/svg/free.svg#cil-account-logout')}}"></use>
            </svg>
            Logout</a> </div>
        </li>
      </ul>
    </div>
    <div class="header-divider"></div>
    <div class="container-fluid">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0 ms-2">
          <li class="breadcrumb-item">
            <!-- if breadcrumb is single-->
            <a href="{{route('admin.dashboard')}}"><span>Dashboard</span> </a></li>
          <li class="breadcrumb-item active"><span>@yield('breadcrumb')</span></li>
        </ol>
      </nav>
    </div>
  </header>

@yield('content')
<footer class="footer">
    <div><a href=""> ©  {{date("Y")}} Fmcg land.</div>
  </footer>
</div>
<!-- CoreUI and necessary plugins-->
<script src="{{asset('/admin1/vendors/@coreui/coreui/js/coreui.bundle.min.js')}}"></script>
<script src="{{asset('/admin1/vendors/simplebar/js/simplebar.min.js')}}"></script>
<!--newly added for file upload preview-->
<script src="{{asset('/admin1/js/jquery1.min.js')}}"></script>
<script>


$(document).on('change', '.file-input', function() {


			var filesCount = $(this)[0].files.length;

			var textbox = $(this).prev();

			if (filesCount === 1) {
			var fileName = $(this).val().split('\\').pop();
			textbox.text(fileName);
			} else {
			textbox.text(filesCount + ' files selected');
			}



			if (typeof (FileReader) != "undefined") {
        var dvPreview = $("#divImageMediaPreview");
        dvPreview.html("");            
        $($(this)[0].files).each(function () {
            var file = $(this);                
                var reader = new FileReader();
                reader.onload = function (e) {
                    var img = $("<img />");
                    img.attr("style", "width: 100px; height:100px; padding: 10px");
                    img.attr("src", e.target.result);
                    dvPreview.append(img);
                }
                reader.readAsDataURL(file[0]);                
        });
    } else {
        alert("This browser does not support HTML5 FileReader.");
    }


			});
    </script>
    
    <link href="{{asset('/admin1/vendors/@coreui/icons/css/free.min.css')}}" rel="stylesheet">

</body>
</html>