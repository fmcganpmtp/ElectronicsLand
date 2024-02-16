<?php  if(!Auth::guard('user')->check()) {  ?>
<!-- Start Subscribe Area -->
<section class="subscribe-area ptb-54 wow fadeInUp">
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
        <div id="newsletter_msg" style="color:green;"><p></p></div>
        <script  type="text/javascript">
                                        $(document).ready(function(){
                                            $( "#submit_news" ).click(function() {
                                            $("#newsletter_msg").empty().append('<p>&nbsp;</p>');
                                            var email = $('#email').val(); 
                                            if(email!='')
                                            { 
                                                $.ajax({
                                                url:"{{route('newsletter.subscription')}}",
                                                type:"POST",
                                                data:{
                                                  "_token": "{{ csrf_token() }}",
                                                  'email':email},
                                                success:function(data){
                                                    $('#email').val('');
                                                    $("#newsletter_msg").empty().append("<p style='color:white'>"+data.replace('"','').replace('"','')+"</p>");
                                                },
                                                error: function (xhr) {
                                                   
                                                    var errors = JSON.parse(xhr.responseText);
                                                    $("#newsletter_msg").empty().append("<p style='color:red'>"+errors.errors.email[0]+"</p>");
                                                    
                                                }
                                                });
                                                
                                            }
                                            });
                                        });
                                       
                                           
                                        
                                        </script>
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
          <?php  if(!empty($view_composer_Flogo->value)) $img_urlf =asset('/assets/uploads/logo/'.$view_composer_Flogo->value);
else $img_urlf =   asset('images/footer-logo.png'); ?>
          <a href=""><img src="{{ $img_urlf }}"></a></div>
          <div class="footer-about">
            <p>
              
            {{ $view_composer_abtSite ?? ''}}</p>
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
            </a></li>
            @else
            <li><a href="{{$view_composer_socialIcon->link}}" target="_blank"><?php  echo $view_composer_socialIcon->icon; ?>
            </a></li>
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
<!--div id="alertCookiePolicy" class="alert-cookie-policy">
  <div class="alert alert-secondary mb-0 d-flex align-items-center" role="alert">
    <span class="mr-auto">This website uses cookies to ensure you get the best experience on our website.  <a href="#" class="alert-link">Learn more</a></span>
    <button id="btnDeclineCookiePolicy" class="btn btn-light mr-3" data-dismiss="alert" type="button" aria-label="Close">Decline</button>
    <button id="btnAcceptCookiePolicy" class="btn btn-primary" data-dismiss="alert" type="button" aria-label="Close">Accept</button>
  </div>  
</div>-->
<!-- End Footer  Area -->
<!-- Start Copy Right Area -->
<div class="copy-right-area">
  <div class="container">
    <p> Copyright © {{date("Y")}} Fmcg land, All rights reserved. Designed and developed by <a href="https://www.hermosoftech.com/" target="_blank">Hermosoftech<img src="{{ asset('images/hermosoftech.png') }}"></a> </p>
  </div>
</div>
<!-- End Copy Right Area -->
<!-- Start Go Top Area -->
<div class="go-top"> <i class="ri-arrow-up-s-fill"></i> <i class="ri-arrow-up-s-fill"></i> </div>
<!-- End Go Top Area -->
<!-- Jquery Min JS -->
    <script defer src="{{ asset('js/cookieconsent.js')}}"></script>
    <script defer src="{{ asset('js/cookieconsent-init.js')}}"></script>

<script src="{{ asset('js/jquery.min.js')}}"></script>
<!-- Bootstrap Bundle Min JS -->
<script src="{{ asset('js/bootstrap.bundle.min.js')}}"></script>
<!-- Meanmenu Min JS -->
<script src="{{ asset('js/meanmenu.min.js')}}"></script>
<!-- Owl Carousel Min JS -->
<script src="{{ asset('js/owl.carousel.min.js')}}"></script>
<!-- Wow Min JS -->
<script src="{{ asset('js/wow.min.js')}}"></script>
<!-- Range Slider Min JS -->
<script src="{{ asset('js/range-slider.min.js')}}"></script>

		<!-- Form Validator Min JS -->
		<script src="{{ asset('js/form-validator.min.js')}}"></script>
		<!-- Contact JS -->
		<!-- Ajaxchimp Min JS -->
		<script src="{{ asset('js/ajaxchimp.min.js')}}"></script>

<!--<script src="{{ asset('js/wow.js')}}"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<!-- Custom JS -->
<script src="{{ asset('js/custom.js')}}"></script>

<script>



$(document).ready(function () {
    $('.hd_srch_btn').prop('disabled', true);
    
    $('.hd_srch').on('keyup', function () {
        var serch_text = $(".hd_srch").val(); 
        if (serch_text != '' ) { 
            $('.hd_srch_btn').prop('disabled', false);
        } else {
            $('.hd_srch_btn').prop('disabled', true);
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

      }

    );

    wow.init();

    // document.getElementById('moar').onclick = function() {

    //   var section = document.createElement('section');

    //   section.className = 'section--purple wow fadeInDown';

    //   this.parentNode.insertBefore(section, this);

    // };

  </script>

<script>
$(document).ready(function(){
  $(".subscr").click(function(){
    $(".shw-btn").show(1000);
  });
});
</script>








<script>
// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})
</script>

<!-- <script>
	var ctx = document.getElementById("myChart").getContext("2d");
	var myChart = new Chart(ctx, {
	type: "line",
	data: {
		labels: [
		"Week 1",
		"Week 2",
		"Week 3",
		"Week 4",
	
		],
		datasets: [
		{
			label: "last month insight",
			data: [100, 400, 75, 800],
			backgroundColor: "rgba(31,119,180,0.6)",
		}
		],
	},
	});
</script> -->
   
   
   <script>
   /* chart.js chart examples */

// chart colors
var colors = ['#007bff','#28a745','#333333','#c3e6cb','#dc3545','#6c757d'];

/* large line chart */


/* large pie/donut chart */
var chPie = document.getElementById("chPie");
if (chPie) {
  new Chart(chPie, {
    type: 'pie',
    data: {
      labels: ['Desktop', 'Phone', 'Tablet', 'Unknown'],
      datasets: [
        {
          backgroundColor: [colors[1],colors[0],colors[2],colors[5]],
          borderWidth: 0,
          data: [50, 40, 15, 5]
        }
      ]
    },
    plugins: [{
      beforeDraw: function(chart) {
        var width = chart.chart.width,
            height = chart.chart.height,
            ctx = chart.chart.ctx;
        ctx.restore();
        var fontSize = (height / 70).toFixed(2);
        ctx.font = fontSize + "em sans-serif";
        ctx.textBaseline = "middle";
        var text = chart.config.data.datasets[0].data[0] + "%",
            textX = Math.round((width - ctx.measureText(text).width) / 2),
            textY = height / 2;
        ctx.fillText(text, textX, textY);
        ctx.save();
      }
    }],
    options: {layout:{padding:0}, legend:{display:false}, cutoutPercentage: 80}
  });
}



/* 3 donut charts */
var donutOptions = {
  cutoutPercentage: 85, 
  legend: {position:'bottom', padding:5, labels: {pointStyle:'circle', usePointStyle:true}}
};

// donut 1
var chDonutData1 = {
    labels: ['Bootstrap', 'Popper', 'Other'],
    datasets: [
      {
        backgroundColor: colors.slice(0,3),
        borderWidth: 0,
        data: [74, 11, 40]
      }
    ]
};

var chDonut1 = document.getElementById("chDonut1");
if (chDonut1) {
  new Chart(chDonut1, {
      type: 'pie',
      data: chDonutData1,
      options: donutOptions
  });
}

// donut 2
var chDonutData2 = {
    labels: ['Wips', 'Pops', 'Dags'],
    datasets: [
      {
        backgroundColor: colors.slice(0,3),
        borderWidth: 0,
        data: [40, 45, 30]
      }
    ]
};
var chDonut2 = document.getElementById("chDonut2");
if (chDonut2) {
  new Chart(chDonut2, {
      type: 'pie',
      data: chDonutData2,
      options: donutOptions
  });
}

// donut 3
var chDonutData3 = {
    labels: ['Angular', 'React', 'Other'],
    datasets: [
      {
        backgroundColor: colors.slice(0,3),
        borderWidth: 0,
        data: [21, 45, 55, 33]
      }
    ]
};
var chDonut3 = document.getElementById("chDonut3");
if (chDonut3) {
  new Chart(chDonut3, {
      type: 'pie',
      data: chDonutData3,
      options: donutOptions
  });
}

/* 3 line charts */
var lineOptions = {
    legend:{display:false},
    tooltips:{interest:false,bodyFontSize:11,titleFontSize:11},
    scales:{
        xAxes:[
            {
                ticks:{
                    display:false
                },
                gridLines: {
                    display:false,
                    drawBorder:false
                }
            }
        ],
        yAxes:[{display:false}]
    },
    layout: {
        padding: {
            left: 6,
            right: 6,
            top: 4,
            bottom: 6
        }
    }
};

var chLine1 = document.getElementById("chLine1");
if (chLine1) {
  new Chart(chLine1, {
      type: 'line',
      data: {
          labels: ['Jan','Feb','Mar','Apr','May'],
          datasets: [
            {
              backgroundColor:'#ffffff',
              borderColor:'#ffffff',
              data: [10, 11, 4, 11, 4],
              fill: false
            }
          ]
      },
      options: lineOptions
  });
}
var chLine2 = document.getElementById("chLine2");
if (chLine2) {
  new Chart(chLine2, {
      type: 'line',
      data: {
          labels: ['A','B','C','D','E'],
          datasets: [
            {
              backgroundColor:'#ffffff',
              borderColor:'#ffffff',
              data: [4, 5, 7, 13, 12],
              fill: false
            }
          ]
      },
      options: lineOptions
  });
}

var chLine3 = document.getElementById("chLine3");
if (chLine3) {
  new Chart(chLine3, {
      type: 'line',
      data: {
          labels: ['Pos','Neg','Nue','Other','Unknown'],
          datasets: [
            {
              backgroundColor:'#ffffff',
              borderColor:'#ffffff',
              data: [13, 15, 10, 9, 14],
              fill: false
            }
          ]
      },
      options: lineOptions
  });
}
function cookiesPolicyPrompt(){ console.log('here');
  if (Cookies.get('acceptedCookiesPolicy') !== "yes"){
    //console.log('accepted policy', chk);
    $("#alertCookiePolicy").show(); 
  }
  $('#btnAcceptCookiePolicy').on('click',function(){
    console.log('btn: accept');
    Cookies.set('acceptedCookiesPolicy', 'yes', { expires: 30 });
    cookiesPolicyPrompt();
  });
  $('#btnDeclineCookiePolicy').on('click',function(){
    //console.log('btn: decline');
    //document.location.href = "https://www.bing.com/search?q=rick+rolled";
  });
}

$( document ).ready(function() {
  cookiesPolicyPrompt();
  
  //-- following not for production ------
  $('#btnResetCookiePolicy').on('click',function(){
    console.log('btn: reset');
    Cookies.remove('acceptedCookiesPolicy');
    $("#alertCookiePolicy").show();
  });
  // ---------------------------
});
   </script>
