
	<!-- Start Footer  Area -->
	<div class="footer-area  ">
	  <div class="container">
		<div class="row">
		  <div class="col-lg-4 col-sm-6">
			<div class="single-footer-widget">
				<?php
								$footer_logo= $site_about = "";			
								foreach($view_composer_general as $general)
								{
								if( $general->item =='footer_logo')
								  $footer_logo=$general->value; 
								 if( $general->item =='site_about')
								  $site_about=$general->value; 	
								}
								if(!empty($footer_logo)) $img_urlf =asset('/assets/uploads/logo/'.$footer_logo);
								else $img_urlf =   asset('images/footer-logo.png'); ?>
			  <div class="footer-logo"><a href=""><img src="{{ $img_urlf }}"></a></div>
			</div>
		  </div>
		  <div class="col-lg-3 col-sm-6">
			<div class="single-footer-widget">
			  <h3>Information</h3>
			  <ul class="import-link">
							@if(count($view_footer_info)>0)
							@foreach($view_footer_info as $data)
							<li><a href="{{url($data->seo_url)}}"> {{$data->page}}</a></li>
							@endforeach
							@endif
							<li><a href="{{route('contactus')}}"> Contact Us</a></li>
						</ul>
			  
			</div>
		  </div>
		  <div class="col-lg-3 col-sm-6">
			<div class="single-footer-widget">
			  <ul class="social-media">
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
		</div>
		<div class="row">
		  <div class="col-lg-12">
			<div class="payment-icon"><img src="{{ asset('images/payment-icon.png') }}"></div>
		  </div>
		</div>
		<div class="copy-right-1">
		  <p> Copyright © {{date("Y")}} Fmcg land, All rights reserved. Designed and developed by <a href="https://www.hermosoftech.com/" target="_blank">HermoSoftech<img src="{{ asset('images/hermosoftech.png') }}"></a> </p>
		</div>
	  </div>
	</div>
	</div>
	<!-- Start Go Top Area -->
	<?php /*?><div class="go-top"> <i class="fa fa-long-arrow-up" aria-hidden="true"></i> </div>
	<?php */?><!-- End Go Top Area -->
	<!-- Jquery Min JS -->
	
	<!-- Meanmenu Min JS -->
	<script src="{{asset('/mobile/js/meanmenu.min.js')}}"></script>
	<!-- Owl Carousel Min JS -->
	<script src="{{asset('/mobile/js/owl.carousel.min.js')}}"></script>
	<!-- Wow Min JS -->
	<script src="{{asset('/mobile/js/wow.min.js')}}"></script>
	<!-- Range Slider Min JS -->
	<script src="{{asset('/mobile/js/range-slider.min.js')}}"></script>
	<!-- Form Validator Min JS -->
	<script src="{{asset('/mobile/js/form-validator.min.js')}}"></script>
	<script src="{{asset('/mobile/js/ajaxchimp.min.js')}}"></script>
	<!--<script src="{{asset('/mobile/js/wow.js')}}"></script>-->
	<script src="{{ asset('/mobile/js/category-menu.js')}}"></script>
<script>
const elchat = document.createElement('div')
elchat.innerHTML = "Chat option is disabled as per the current package.Would you like to upgrade package? <a href='{{route('package.listing')}}'>Upgrade</a>"
    function showerrorchat(){
  swal({
  icon: "error",
  content: elchat,
});
}

const elprof = document.createElement('div')
elprof.innerHTML = "Profile view is disabled as per the current package.Would you like to upgrade package? <a href='{{route('package.listing')}}'>Upgrade</a>"
    function showerrorprofile(){
  swal({
  icon: "error",
  content: elprof,
});
}


const elnetwork = document.createElement('div')
elnetwork.innerHTML = "Expand Your Network option is disabled as per the current package. Would you like to upgrade package? <a href='{{route('package.listing')}}'>Upgrade</a>"
    function showerrornetwork(){
  swal({
  icon: "error",
  content: elnetwork,
});
}

const elinsight = document.createElement('div')
elinsight.innerHTML = "Insight option is disabled as per the current package. Would you like to upgrade package? <a href='{{route('package.listing')}}'>Upgrade</a>"
    function showerrorInsight(){
  swal({
  icon: "error",
  content: elinsight,
});
}



const elCrProdReq = document.createElement('div')
elCrProdReq.innerHTML = "Create Product request  is disabled as per the current package. Would you like to upgrade package? <a href='{{route('package.listing')}}'>Upgrade</a>"
    function showerrorPrdReq(){
  swal({
  icon: "error",
  content: elCrProdReq,
});
}

const elProdReq = document.createElement('div')
elProdReq.innerHTML = "Product request  is disabled as per the current package. Would you like to upgrade package? <a href='{{route('package.listing')}}'>Upgrade</a>"
    function showerrorPrdReq(){
  swal({
  icon: "error",
  content: elProdReq,
});
}

const elMyPrdReq = document.createElement('div')
elMyPrdReq.innerHTML = "My Product request option is disabled as per the current package. Would you like to upgrade package? <a href='{{route('package.listing')}}'>Upgrade</a>"
    function showerrorMyPrdReq(){
  swal({
  icon: "error",
  content: elMyPrdReq,
});
}

</script> 
	<script>

		wow = new WOW(

		  {

			animateClass: 'animated',

			offset:       100,

			callback:     function(box) {

			  console.log("WOW: animating <" + box.tagName.toLowerCase() + ">")

			}

		  }

		);

		wow.init();

	  </script>
	<!-- Custom JS -->
	<script src="{{asset('/mobile/js/custom.js')}}"></script>
	<script  type="text/javascript">



		$fmcg(document).ready(function(){

				
	   var getMenuItem = function(itemData) {
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
		                   
		                    var mobilemenu_1 = $fmcg("#mobilemenu_1");
		                    $fmcg.each(menu_structure_dt.menu, function() {
		                    mobilemenu_1.append(getMenuItem(this));
		                    
		                    });
		                  
		     mobilemenu_1.menumaker({
		      format: "multitoggle"
		   });        
		         } ,
             error: function(XMLHttpRequest, textStatus, errorThrown) { 
               
             }  
      
            })  ;

			$fmcg("#categoryButton2").click(function(){
				$fmcg(".new-cat-menu").slideToggle();
			  });
			$fmcg(".menu-btn").click(function(){
			  $fmcg("p").hide();
			});

			// $fmcg(".menu-btn").click(function(){
			//   $fmcg("#cssmenu ul").slideRight()
			// });
			$fmcg( "#submit_news" ).click(function() {
			var email = $fmcg('#email').val(); 
			if(email!='')
			{ 
			    var load_image='{{ asset('images/ajax-loder.gif')}}';
	            var loading_image_newltr='<img src="'+load_image+'" alt="" style="width:27px;" />';
	            $fmcg("#newsletter_msg").empty().append(loading_image_newltr);
				$fmcg.ajax({
				url:"{{route('newsletter.subscription')}}",
				type:"POST",
				data:{
				  "_token": "{{ csrf_token() }}",
				  'email':email},
				   success:function(data){
					$fmcg('#email').val('');
					$fmcg("#newsletter_msg").empty().append("<p style='color:white'>"+data.replace('"','').replace('"','')+"</p>");
				},
				error: function (xhr) {
				   var errors = JSON.parse(xhr.responseText);
				   $fmcg("#newsletter_msg").empty().append("<p style='color:red'>"+errors.errors.email[0]+"</p>");
					
				}
				});
				
			}
			});
		});
		$fmcg(document).ready(function () {
		$fmcg('.hd_srch_btn').prop('disabled', true);
		
		$fmcg('.hd_srch').on('keyup', function () {
		var serch_text = $fmcg(".hd_srch").val(); 
		if (serch_text != '' ) { 
		$fmcg('.hd_srch_btn').prop('disabled', false);
		} else {
		$fmcg('.hd_srch_btn').prop('disabled', true);
		}
		});
		});
		wow = new WOW(
		{
		animateClass: 'animated',
		offset:       100,
		callback:     function(box) {
		console.log("WOW: animating <" + box.tagName.toLowerCase() + ">")
		}
		});
		wow.init();
	</script>
	<script>
	$fmcg(document).ready(function(){
	  $fmcg(".subscr").click(function(){
		$fmcg(".shw-btn").show(1000);
	  });
	});
	</script>
	<!-- Modal -->
	</body>
	</html>
	<script>
	// Initialize tooltips
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
	  return new bootstrap.Tooltip(tooltipTriggerEl)
	})
	</script>


	
	<div class="mobile-responsive-menu">
	<div id="mySidenav" class="sidenav"> <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
	  <div id='cssmenu'>
		<ul class="fix-height" id="mobilemenu_1">
		  
		</ul> 
	 </div>
	</div>
	</div>
	<script>
	function openNav() {
		document.getElementById("mySidenav").style.width = "100%";
	}

	function closeNav() {
		document.getElementById("mySidenav").style.width = "0";
	}
	</script>
	
	</div>
