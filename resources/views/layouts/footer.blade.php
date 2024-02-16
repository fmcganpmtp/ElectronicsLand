<?php  if(!Auth::guard('user')->check()) {  ?>
<!-- Start Subscribe Area -->
<section class="subscribe-area ptb-54 wow">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-7">
				<div class="subscribe-content">
					<h3>Subscribe To Our Newsletter</h3>
					<p>The latest offers, the best deals and everything that is happening in the world of FMCG.</p>
				</div>
			</div>
			<div class="col-lg-5">
				<div class="newsletter-form">
					<input name="email" id="email" type="text" class="form-control {{ $errors->has('email')? ' is-invalid':''}}" placeholder="Your email address" required >
					@if ($errors->has('email'))
					<span class="invalid-feedback" role="alert">
					<strong>{{ $errors->first('email') }}</strong>
					</span>
					@endif
					<button class="submit-btn default-btn" id="submit_news" type="submit"> Subscribe </button>
				</div>
				<div id="newsletter_msg" style="color:green;"><p>&nbsp;</p></div>
			</div>
		</div>
	</div>
</section>
<?php } ?>
<!-- Start Footer  Area -->
<div class="footer-area pt-54 pb-30">
	<div class="container">
		<div class="row">
			<div class="col-lg-4 col-sm-6">
				<div class="single-footer-widget">
					<div class="footer-logo">
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
						<a href=""><img src="{{ $img_urlf }}"></a>
					</div>
					<div class="footer-about">
						<p>
							{{ $site_about ?? ''}}
						</p>
					</div>
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
			@if(count($view_footer_help)>0)
			<div class="col-lg-2 col-sm-6">
				<div class="single-footer-widget">
					<h3>Help</h3>
					<ul class="import-link">
						@foreach($view_footer_help as $data)
						<li><a href="{{url($data->seo_url)}}"> {{$data->page}}</a></li>
						@endforeach
					</ul>
				</div>
			</div>
			@endif
			<div class="col-lg-3 col-sm-6">
				<div class="single-footer-widget">
					<h3>Stay Connected </h3>
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
	</div>
</div>
</div>
<!-- End Footer  Area -->
<!-- Start Copy Right Area -->
<div class="copy-right-area">
	<div class="container">
		<p> Copyright © {{date("Y")}} Fmcg land, All rights reserved. Designed and developed by <a href="https://www.hermosoftech.com/" target="_blank">HermoSoftech<img src="{{ asset('images/hermosoftech.png') }}"></a> </p>
	</div>
</div>

<!-- End Copy Right Area -->
<!-- Start Go Top Area -->
<div class="go-top"> <i class="ri-arrow-up-s-fill"></i> <i class="ri-arrow-up-s-fill"></i> </div>
<!-- End Go Top Area -->
<!-- Jquery Min JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
        <script defer src="{{ asset('js/cookieconsent.js')}}"></script>
        <script defer src="{{ asset('js/cookieconsent-init.js')}}"></script>
        <!-- Bootstrap Bundle Min JS -->
        <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
        <!-- Meanmenu Min JS -->
        <script src="{{ asset('js/meanmenu.min.js')}}"></script>
        <!-- Owl Carousel Min JS -->
        <script src="{{ asset('js/owl.carousel.min.js')}}"></script>
        <!-- Wow Min JS -->
        <script src="{{ asset('js/wow.min.js')}}"></script>
        <script src="{{ asset('js/form-validator.min.js')}}"></script>
        <script src="{{ asset('js/ajaxchimp.min.js')}}"></script>
        <script src="{{ asset('js/wow.js')}}"></script>
		<!-- Custom JS -->
        <script src="{{ asset('js/custom.js')}}"></script>
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

const elProdReq = document.createElement('div')
elProdReq.innerHTML = "Product request  is disabled as per the current package. Would you like to upgrade package? <a href='{{route('package.listing')}}'>Upgrade</a>"
    function showerrorPrdReq(){
  swal({
  icon: "error",
  content: elProdReq,
});
}

const elCrProdReq = document.createElement('div')
elCrProdReq.innerHTML = "Create Product request  is disabled as per the current package. Would you like to upgrade package? <a href='{{route('package.listing')}}'>Upgrade</a>"
    function showerrorCrPrdReq(){
  swal({
  icon: "error",
  content: elCrProdReq,
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
<script  type="text/javascript">
//var $fmcg = $.noConflict();
	$fmcg(document).ready(function(){
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
	    
	    $fmcg(".subscr").click(function(){
	            $fmcg(".shw-btn").show(1000);
	    });
	   $fmcg("#categoryButton2").click(function(){
              $fmcg(".menu-backdrop2").toggle();
        });
      $fmcg(".menu-backdrop2").click(function(){
    	    $fmcg(".menu-backdrop2").hide();
    	  });
    	  $fmcg(".menu-backdrop2").click(function(){
    	    $fmcg(".new-cat-menu").hide();
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




