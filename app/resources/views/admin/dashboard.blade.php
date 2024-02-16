@extends('admin.master')
@section('title')
FMCG | Dashboard
@endsection
@section('content')


  
  <div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="row">
        <div class="col-sm-6 col-lg-3">
          <div class="card mb-4 text-white bg-primary">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
              <div>
                <div class="fs-4 fw-semibold">{{$total_chat_usage}} <span class="fs-6 fw-normal"><!---(12.4%
                  <svg class="icon">
                    <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-arrow-bottom"></use>
                  </svg>
                  )--></span></div>
                <div>Chat Insight</div>
              </div>
             
            </div>
            <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
              <canvas class="chart" id="card-chart1" height="70"></canvas>
            </div>
          </div>
        </div>
        <!-- /.col-->
        <div class="col-sm-6 col-lg-3">
          <div class="card mb-4 text-white bg-info">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
              <div>
                <div class="fs-4 fw-semibold">{{$engached_users}}</div>
                <div>Engaged Users</div>
              </div>
             
            </div>
            <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
              <canvas class="chart" id="card-chart2" height="70"></canvas>
            </div>
          </div>
        </div>
        <!-- /.col-->
        <div class="col-sm-6 col-lg-3">
          <div class="card mb-4 text-white bg-warning">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
              <div>
                <div class="fs-4 fw-semibold">{{$totalProducts}} </div>
                <div>Products</div>
              </div>
              <!--<div class="dropdown">-->
              <!--  <button class="btn btn-transparent text-white p-0" type="button" data-coreui-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
              <!--  <svg class="icon">-->
              <!--    <use xlink:href="{{url('/admin1/vendors/@coreui/icons/svg/free.svg#cil-options')}}"></use>-->
              <!--  </svg>-->
              <!--  </button>-->
              <!--  <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another action</a><a class="dropdown-item" href="#">Something else here</a></div>-->
              <!--</div>-->
            </div>
            <div class="c-chart-wrapper mt-3" style="height:70px;">
              <canvas class="chart" id="card-chart3" height="70"></canvas>
            </div>
          </div>
        </div>
        <!-- /.col-->
        <div class="col-sm-6 col-lg-3">
          <div class="card mb-4 text-white bg-danger">
            <div class="card-body pb-0 d-flex justify-content-between align-items-start">
              <div>
                <div class="fs-4 fw-semibold">150 <!--<span class="fs-6 fw-normal">(-23.6%
                  <svg class="icon">
                    <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-arrow-bottom"></use>
                  </svg>
                  )</span>--></div>
                <div>Subscriptions</div>
              </div>
              <div class="dropdown">
                <button class="btn btn-transparent text-white p-0" type="button" data-coreui-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <svg class="icon">
                  <use xlink:href="{{url('/admin1/vendors/@coreui/icons/svg/free.svg#cil-options')}}"></use>
                </svg>
                </button>
                <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another action</a><a class="dropdown-item" href="#">Something else here</a></div>
              </div>
            </div>
            <div class="c-chart-wrapper mt-3 mx-3" style="height:70px;">
              <canvas class="chart" id="card-chart4" height="70"></canvas>
            </div>
          </div>
        </div>
        <!-- /.col-->
      </div>
      <!-- /.row-->

       <div class="card mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <h4 class="card-title mb-0">Chat Insight Report</h4>
              <div class="small text-medium-emphasis" id="txtperiod"></div>
            </div>
            <div class="btn-toolbar d-none d-md-block" role="toolbar" aria-label="Toolbar with buttons">
              <div class="btn-group btn-group-toggle mx-3" data-coreui-toggle="buttons">
                <input class="btn-check" id="option2" type="radio" name="options" autocomplete="off">
                <label class="btn btn-outline-secondary" id="lbloption2" onclick="fnchat_insight('last_week','lbloption2')"> Last Week</label>
                <input class="btn-check" id="option3" type="radio" name="options" autocomplete="off" >
                <label class="btn btn-outline-secondary" id="lbloption3" onclick="fnchat_insight('last_month','lbloption3')"> Last Month</label>
              </div>
              
            </div>
          </div>
                <div class="card-body" id="divchatusers">   
                  <div class="set-graph-height"> </div>
                    <canvas id="chLine_new"></canvas>
                  </div>
        </div>
       
      </div>

      <div class="card mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <h4 class="card-title mb-0">Engaged Users Report</h4>
              <div class="small text-medium-emphasis" id="txtenperiod"></div>
            </div>
            <div class="btn-toolbar d-none d-md-block" role="toolbar" aria-label="Toolbar with buttons">
              <div class="btn-group btn-group-toggle mx-3" data-coreui-toggle="buttons">
                <input class="btn-check" id="enoption2" type="radio" name="options" autocomplete="off">
                <label class="btn btn-outline-secondary" id="lblenoption2" onclick="fnEngagedUsers('last_week','lblenoption2')"> Last Week</label>
                <input class="btn-check" id="enoption3" type="radio" name="options" autocomplete="off" >
                <label class="btn btn-outline-secondary" id="lblenoption3" onclick="fnEngagedUsers('last_month','lblenoption3')"> Last Month</label>
              </div>
              
            </div>
          </div>
                <div class="card-body">   
                  <div class="set-graph-height"> </div>
                    <canvas id="chenLine_new"></canvas>
                  </div>
        </div>
       
      </div>

      <!--<div class="card mb-4">-->
      <!--  <div class="card-body">-->
      <!--    <div class="d-flex justify-content-between">-->
      <!--      <div>-->
      <!--        <h4 class="card-title mb-0">Product Enquiry</h4>-->
      <!--        <div class="small text-medium-emphasis">January - July 2022</div>-->
      <!--      </div>-->
      <!--      <div class="btn-toolbar d-none d-md-block" role="toolbar" aria-label="Toolbar with buttons">-->
      <!--        <div class="btn-group btn-group-toggle mx-3" data-coreui-toggle="buttons">-->
      <!--          <input class="btn-check" id="option1" type="radio" name="options" autocomplete="off">-->
      <!--          <label class="btn btn-outline-secondary"> Day</label>-->
      <!--          <input class="btn-check" id="option2" type="radio" name="options" autocomplete="off" checked="">-->
      <!--          <label class="btn btn-outline-secondary active"> Month</label>-->
      <!--          <input class="btn-check" id="option3" type="radio" name="options" autocomplete="off">-->
      <!--          <label class="btn btn-outline-secondary"> Year</label>-->
      <!--        </div>-->
      <!--        <button class="btn btn-primary" type="button">-->
      <!--        <svg class="icon">-->
      <!--          <use xlink:href="{{url('/admin1/vendors/@coreui/icons/svg/free.svg#cil-cloud-download')}}"></use>-->
      <!--        </svg>-->
      <!--        </button>-->
      <!--      </div>-->
      <!--    </div>-->
      <!--    <div class="c-chart-wrapper" style="height:300px;margin-top:40px;">-->
      <!--      <canvas class="chart" id="main-chart" height="300"></canvas>-->
      <!--    </div>-->
      <!--  </div>-->
       
      <!--</div>-->
      <!--<div class="row">-->
      <!--  <div class="col-sm-6 col-lg-4">-->
      <!--    <div class="card mb-4" style="--cui-card-cap-bg: #3b5998">-->
      <!--      <div class="card-header position-relative d-flex justify-content-center align-items-center">-->
              
      <!--        <div class="chart-wrapper position-absolute top-0 start-0 w-100 h-100">-->
      <!--          <canvas id="social-box-chart-1" height="90"></canvas>-->
      <!--        </div>-->
      <!--      </div>-->
      <!--      <div class="card-body row text-center">-->
      <!--        <div class="col">-->
      <!--          <div class="fs-5 fw-semibold">Automotive</div>-->
      <!--       </div>-->
      <!--        <div class="vr"></div>-->
      <!--        <div class="col">-->
      <!--          <div class="fs-5 fw-semibold">459+</div>-->
      <!--       </div>-->
      <!--      </div>-->
      <!--    </div>-->
      <!--  </div>-->
      <!--  <div class="col-sm-6 col-lg-4">-->
      <!--    <div class="card mb-4" style="--cui-card-cap-bg: #00aced">-->
      <!--      <div class="card-header position-relative d-flex justify-content-center align-items-center">-->
            
      <!--        <div class="chart-wrapper position-absolute top-0 start-0 w-100 h-100">-->
      <!--          <canvas id="social-box-chart-2" height="90"></canvas>-->
      <!--        </div>-->
      <!--      </div>-->
      <!--      <div class="card-body row text-center">-->
      <!--        <div class="col">-->
      <!--          <div class="fs-5 fw-semibold">Clothing</div>-->
      <!--        </div>-->
      <!--        <div class="vr"></div>-->
      <!--        <div class="col">-->
      <!--          <div class="fs-5 fw-semibold">1500+</div>-->
      <!--        </div>-->
      <!--      </div>-->
      <!--    </div>-->
      <!--  </div>-->
      <!--  <div class="col-sm-6 col-lg-4">-->
      <!--    <div class="card mb-4" style="--cui-card-cap-bg: #4875b4">-->
      <!--      <div class="card-header position-relative d-flex justify-content-center align-items-center">-->
            
      <!--        <div class="chart-wrapper position-absolute top-0 start-0 w-100 h-100">-->
      <!--          <canvas id="social-box-chart-3" height="90"></canvas>-->
      <!--        </div>-->
      <!--      </div>-->
      <!--      <div class="card-body row text-center">-->
      <!--        <div class="col">-->
      <!--          <div class="fs-5 fw-semibold">Electronics</div>-->
      <!--        </div>-->
      <!--        <div class="vr"></div>-->
      <!--        <div class="col">-->
      <!--          <div class="fs-5 fw-semibold">1300+</div>-->
      <!--        </div>-->
      <!--      </div>-->
      <!--    </div>-->
      <!--  </div>-->
      <!--</div>-->
    
    </div>
<script src="{{asset('/admin1/js/chart_admin.js')}}"></script>
<script src="{{asset('/admin1/js/sweetalert.js')}}"></script>

<script type="text/javascript">
fnchat_insight('last_week','lbloption2');  
fnEngagedUsers('last_week','lblenoption2');  
var insight_users_chart=engaged_users_chart=null;
function fnEngagedUsers(value,type){
  var colors = ['#007bff','#28a745','#333333','#c3e6cb','#dc3545','#6c757d'];
  $(".loaderajax").show(); 
  if(type=='lblenoption2')
  {
    $("#"+type).addClass('active');
    $("#lblenoption3").removeClass('active');
  }
  else
  {
    $("#"+type).addClass('active');
    $("#lblenoption2").removeClass('active');
  }  
  $.ajax({
         url: "{{ url('get_engagedusers') }}",
            type: "post",
            data:{ 
                _token:'{{ csrf_token() }}',
                  type: value,
            },
            async:false,
            cache: false,
            dataType: 'json',
            success: function(successdata1){
              $("#txtenperiod").empty().append(successdata1.period);
             $(".loaderajax").hide();
             var max = Math.max(...successdata1.engaged_users);
             var char_step=2*Math.round(max / 6);
             if(char_step==0)
                char_step=10;
             var chenLine_new = document.getElementById("chenLine_new");
            
            var chartData1 = {
              labels: successdata1.label,
              datasets: [{
                data: successdata1.engaged_users,
                backgroundColor: 'transparent',
                borderColor: colors[0],
                borderWidth: 4,
                pointBackgroundColor: colors[0]
              }
            
              ]
            };
            engaged_users_chart=null;
            if (chenLine_new) {
                engaged_users_chart =new Chart(chenLine_new, {
              type: 'line',
              data: chartData1,
              options: {
                  
                scales: {
                     yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: char_step
                            }
                        }],
                  xAxes: [{
                    ticks: {
                      beginAtZero: true
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
          } ,
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
               $(".loaderajax").hide();
              swal("Error: " + errorThrown,'','error'); 
          }  

        })  ;

}

function fnchat_insight(value,type){
  $("#chLine_new").remove();
  $("#divchatusers").append('<canvas id="chLine_new"></canvas>');
  $(".loaderajax").show(); 
  if(type=='lbloption2')
  {
    $("#"+type).addClass('active');
    $("#lbloption3").removeClass('active');
  }
  else
  {
    $("#"+type).addClass('active');
    $("#lbloption2").removeClass('active');
  } 
  var colors = ['#007bff','#28a745','#333333','#c3e6cb','#dc3545','#6c757d'];
  $.ajax({
         url: "{{ url('getchat_insight') }}",
            type: "post",
            data:{ 
                _token:'{{ csrf_token() }}',
                  type: value,
            },
            async:false,
            cache: false,
            dataType: 'json',
            success: function(successdata){
              $("#txtperiod").empty().append(successdata.period);
             $(".loaderajax").hide();
             var max = Math.max(...successdata.chat_insight);
             var char_step=2*Math.round(max / 6);
             if(char_step==0)
                char_step=10;
             var chLine_new = document.getElementById("chLine_new");
              var chartData = {
                  labels: successdata.label,
                  datasets: [{
                    data: successdata.chat_insight,
                    backgroundColor: 'transparent',
                    borderColor: colors[0],
                    borderWidth: 4,
                    pointBackgroundColor: colors[0]
                  }
                
                  ]
                };
                insight_users_chart=null;
                if (chLine_new) {
                   insight_users_chart = new Chart(chLine_new, {
                  type: 'line',
                  data: chartData,
                  options: {
                      
                    scales: {
                         yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    stepSize: char_step
                                }
                            }],
                      xAxes: [{
                        ticks: {
                          beginAtZero: true
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
     

          } ,
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
               $(".loaderajax").hide();
              swal("Error: " + errorThrown,'','error'); 
          }  

        })  ;

}
</script>

@endsection