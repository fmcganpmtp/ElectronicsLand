@extends('layouts.template_mobile')
@section('title', 'Dashboard')
@section('content')



<sectiion class="seller-page no-bg white-bg bottom-no-padding">
  <div class="container">
      
      <div class="row">
      <div class="col-lg-12">
        <div class="page-title">
          <h1>Seller Dashboard</h1>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="pr-bottom">

		  
		  
		  <div class=" wow fadeInUp color-bx-out">
            <div class="row">
            
              <div class="col-lg-3 col-6">
              
              <a href="{{route('seller.products')}}">
                <div class="item-block bx1">
                  <div class="dash-ic"><img src="{{asset('assets/images/stock.png')}}"></div>
                  <h4>
                    <span>Products</span> </h4>
                </div></a>
              </div>
              
             
              <div class="col-lg-3 col-6">
             
              <a href="{{route('user.mynetwork')}}">
                <div class="item-block bx2">
                  <div class="dash-ic"><img src="{{asset('assets/images/category-ic.png')}}"></div>
                  <h4>
                    <span>Networks</span> </h4>
                </div></a>
                
              </div>
              
              
              <div class="col-lg-3 col-6">
                  @if($view_composer_profile_menu_visible_criteria['general_profileInsights'] == 1)
              <a href="{{route('BusinessInsight')}}">
                <div class="item-block bx4">
                  <div class="dash-ic"><img src="{{asset('assets/images/notification.png')}}"></div>
                  <h4>
                    <span>Insight</span> </h4>
                </div></a>
                @else
                <a  onclick="showerrorInsight()" href="JavaScript:void(0);">
                <div class="item-block bx4">
                  <div class="dash-ic"><img src="{{asset('assets/images/notification.png')}}"></div>
                  <h4>
                    <span>Insight</span> </h4>
                </div></a>
                @endif
              </div>
              
              
              <div class="col-lg-3 col-6">
              <a href="{{ route('subscription.details')}}">
                <div class="item-block bx3">
                  <div class="dash-ic"><img src="{{asset('assets/images/sbscripton.png')}}"></div>
                  <h4>
                    <span>subscripton</span> </h4>
                </div></a>
              </div>
              
              
              
              
              
            </div>
          </div>
		  
		  
		  
		  
		  
		  
		  

		  

      </div>
    </div>
  </div>
  </div>
  
  
  
  <div class="counting-sec"> 
  
  <div class="container">
  
             
            <div class="row">
               
              <div class="col-lg-4 col-12">
                <div class="slr-counting">
                  <div class="row">
                    <div class="col-lg-3 col-4">
                      <div class="pro-countinf-ic"><img src="{{ URL::asset('assets/images/view.png')}}"></div>
                    </div>
                    <div class="col-lg-9 col-8">
                      <h4>{{$profile_visit_count}}+</h4>
                      <h5> Profile viewers </h5>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-12">
                <div class="slr-counting">
                  <div class="row">
                    <div class="col-lg-3 col-4">
                      <div class="pro-countinf-ic"><img src="{{ URL::asset('assets/images/growth.png')}}"></div>
                    </div>
                    <div class="col-lg-9 col-8">
                      <h4>{{$network_count}}</h4>
                      <h5>Network Connections</h5>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-12">
                <div class="slr-counting">
                  <div class="row">
                    <div class="col-lg-3 col-4">
                      <div class="pro-countinf-ic"><img src="{{ URL::asset('assets/images/product-ct.png')}}"></div>
                    </div>
                    <div class="col-lg-9 col-8">
                      <h4>{{$product_count}}+</h4>
                      <h5>Products</h5>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div></div>
  
</sectiion>
<script type="text/javascript">
     
     
        $fmcg(document).ready( function () {
           
          loadproductss();
        });
     



function loadproductss(){

  <?php if(!empty(request()->route('profId'))) $user_id= request()->route('profId') ; 
  else $user_id= Auth::guard('user')->user()->id; 
  
  //$user_id= request()->route('profId');
  ?>


var user_id = <?php echo $user_id; ?>

$fmcg.ajax({
                            type : 'post',
                            url:"{{route('loadproducts')}}",
                            data:{
                              _token:'{{ csrf_token() }}',
                              'user_id':user_id,
                              'start_from':$fmcg('#start_from').val(),
                              'per_page':4
                            },
                           

                            success:function(data)
                            {
                                  
                                   let dataItems = JSON.parse(data);
                                   console.log();
                                   var initial=0;
                                   var string ='';
                                   dataItems.loadProducts.map((item) => {
                                    initial++;
                                   
                                    var app_path="<?php echo url('seller-product-detail/')?>";
                                    var item_url="{{route('view.Sproduct', ':prd_id')}}";
                                string+='<div class="col-lg-3 col-12 wow fadeInUp">';
                                string+='<a  href='+app_path+'/'+item.prd_id+' >';
                                string+='<div class="product-thumbnail hovereffect">';
                                string+='<div class="pro-img"><img src="'+item.prd_img+'">';
                                string+='<div class="product-title">';
                                string+='<h3>'+item.name+'</h3></div></div></div></a></div>';
								                
                                })
                                $fmcg("#product-listing").append(string);
                               
                                   var skipcount=parseInt($fmcg("#start_from").val())+ initial ;
                                    if(dataItems.count!=skipcount)
                                      $fmcg("#product-listing").append('<div class="row"><div class="default-btn" style="width:15%;margin:15px auto;" id="loadmore" onclick="loadmore()">Load More</div></div>');

                                   $fmcg("#start_from").val(skipcount);
         
                                   
                            } ,
                             error: function(XMLHttpRequest, textStatus, errorThrown) { 
                                
                              // swal(errorThrown, "", "error");
                             }  


                      });

}


function EnableChatRequest(seller_id){
        $fmcg(".message-input").css("display", "block");
      
                 $fmcg("#selected_id").val(seller_id);
                 loadMessage();
                  $fmcg("#frame").show();
                  senter = seller_id;
            
        
}



function loadmore(){
  $fmcg("#loadmore").remove();
  loadproductss();
}



  function addtonetwork(userid)
   {
    $fmcg.ajax({
            url: "{{ url('profile_to_network') }}",
               type: "get",
               data:{ 
                   user_id: userid,
               },
               async:true,
               cache: false,
               dataType: 'json',
               success: function(data){
                 swal(data, "You clicked the button!", "success");
                 location.reload();
             } ,
             error: function(XMLHttpRequest, textStatus, errorThrown) { 
               swal(errorThrown, "You clicked the button!", "error");
             }  
   
           })  ;
  }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script>
var xValues = ["Italy", "France", "Spain", "USA", "Argentina"];
var yValues = [55, 49, 44, 24, 15];
var barColors = ["red", "green","blue","orange","brown"];

new Chart("myChart", {
  type: "bar",
  data: {
    labels: xValues,
    datasets: [{
      backgroundColor: barColors,
      data: yValues
    }]
  },
  options: {
    legend: {display: false},
    title: {
      display: true,
      text: "Graph Title"
    }
  }
});
</script>

           
<script>
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
    </script>
	
	<script>
   /* chart.js chart examples */

// chart colors
var colors = ['#007bff','#28a745','#333333','#c3e6cb','#dc3545','#6c757d'];

/* large line chart */
var chLine_new = document.getElementById("chLine_new");

var chartData = {
  labels: ["Week 1", "Week 2", "Week 3", "Week 4"],
  datasets: [{
    data: [<?=$profile_views_week1?>, <?=$profile_views_week2?>, <?=$profile_views_week3?>, <?=$profile_views_week4?>],
    backgroundColor: 'transparent',
    borderColor: colors[0],
    borderWidth: 4,
    pointBackgroundColor: colors[0]
  }

  ]
};
if (chLine_new) {
  new Chart(chLine_new, {
  type: 'line',
  data: chartData,
  options: {
    scales: {
         yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }],
      xAxes: [{
        ticks: {
          beginAtZero: false
        }
      }]
    },
    legend: {
      display: false
    },
    responsive: true
  }
  });
}
	
	
</script>	
	
	
	
@endsection
@section('footer_script')


@endsection