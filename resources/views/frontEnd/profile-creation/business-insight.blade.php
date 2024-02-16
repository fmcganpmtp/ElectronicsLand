@extends('layouts.template')
@section('title', 'Business Insight')
@section('content')
<style>
table.dataTable thead .sorting::after {
        top: 101px;
}
</style>

<sectiion class="seller-page no-bg">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="pr-bottom">
          <div class="card">
            <div class="row">
              <div class="col-lg-12">
                <h3>Insights</h3>
              </div>
            </div>
            <div class="ctr">
              <div class="row">
                
                @foreach($category_product_count as $data)
                @if(!is_null($data['category'])&&$data['product_count']!=0)
                <div class="col-lg-3 col-12">
                  <div class="counter blue">
                    <div class="counter-icon"><?php 
          
            if((is_null($data['category']->category_pic)) )
                $img_url = asset('uploads/defaultImages/pop-ic-4.png');
            else
                $img_url =asset('/uploads/categoryImages/'.$data['category']['category_pic']); 
                 
    
            ?><img src="{{ $img_url }}"></div>
                    <span class="counter-value">{{$data['product_count']}}</span>
                    <h4>{{$data['category']['name'] ?? ""}}</h4>
                  </div>
                </div>
                @endif
                @endforeach
               
              </div>
            </div> 
            
          </div>
		  
                
               
               
              




		  
		  
		  
          <div class="card">
            
            <script src=
"https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.2/Chart.min.js"></script>
            <!--         <div class="chart-out">
              <canvas id="myChart"></canvas>
            </div>-->
            <div class="row my-2">
              <div class="col-md-6 py-1">
                <h3>Customer visit</h3>
                <div class="card">
                    
                    
                  <div class="card-body">   
                  <div class="set-graph-height"> </div>
                    <canvas id="chLine_new"></canvas>
                  </div>
                </div>
              </div>
              <div class="col-md-6 py-1">
                <h3>Last 7 Days insight</h3>
                <div class="card">
                  <div class="color-denote">
                    <label class="c-bx"><span class="grn"></span>Repeated users</label>
                    <label class="c-bx"><span class="blu"></span>New users</label>
                  </div>
                  <div class="card-body">
                    <canvas id="chBar_new"></canvas>
                  </div>
                </div>
              </div>
            </div>
			
			
			
			
			<div class="row my-2">
             
              <div class="col-md-12 py-1">
                <h3>Country Insight</h3>
                <div class="card">
                 
                  <div class="card-body" style="max-height:700px !important;">
                    <canvas id="chBar_country"></canvas>
                    
                    <p></p>
                  </div>
                </div>
              </div>
            </div>
			
		
			
          </div>
		  
		  
		  
		  
		          <div class="card ">
            <div class="row">
              <div class="col-lg-12">
                <h3> Category list</h3>
              </div>
            </div>
            
         <div class="tableC">
                    <table  id="dataTableCategory" class="table table-striped table-bordered datatable" data-page-length='20' cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th></th>
                   <th>Sl no</th>
                    <th>Category name</th>
                    <th>No of visitors all</th>
                    <th>Last week visit</th>
                    <th> Last month</th>
                    <th>Repeated visit count</th>
                  </tr>
                </thead>
                
              </table>
            </div>
           
          </div>
          <div class="card ">
            <div class="row">
              <div class="col-lg-12">
                <div class="h3-titile mr-btm">
                  <h4>Products</h4>
                  <div class="buyer-filt insight-srch">
                      
                      
                      
                  
                      
                      
                       <input type="search" id="search_key"  placeholder="Search...." class="form-control" >
                       <button type="submit" class="search-btn inner-srch" btnsearch> <i class="ri-search-line"></i> </button>
                      
                      
                    
                  </div>
                </div>
              </div>
            </div>
			
			
			
			 <div class="tableC">
                    <table  id="datatable" class="table table-striped table-bordered datatable" data-page-length='20' cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th></th>
                   <th>Sl no</th>
                    <th>Product name</th>
                    <th>No of visitors all</th>
                    <th>Last week visit</th>
                    <th> Last month</th>
                    <th>Repeated visit count</th>
                  </tr>
                </thead>
                
              </table>
            </div>

       
          </div>  
		  

      </div>
    </div>
  </div>
  </div>
</sectiion>
<script src="{{asset('/js/datatable.js')}}"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
     
     var id = '12';



    $fmcg('#btnsearch').click(function(){
    dataTable.draw();
    });
    $fmcg("#search_key").keydown(function (event) { 
     if (event.which == 13) { 
         event.preventDefault();
         dataTable.draw();
     }
    });
   var dataTable = $fmcg('#datatable').DataTable({
          "processing": true,
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
         serverSide: true,
         'searching': false,
         "lengthChange": false,
         "order": [ 0,'desc'],
         'ajax': {
          'url':"{{ url('getbusinessproducts') }}",

          'data': function(data){
          _token="{{csrf_token()}}";
          data.search_key = $fmcg("#search_key").val();
           
        },
      
    }, 
   
    "columnDefs":[
      {
       "targets":0, 
       "orderable": true,
       "visible":false
      },
      {
       "targets":1, 
       "orderable": true,
       "render": function(data,type,full,meta)
      {
         return meta.row + meta.settings._iDisplayStart + 1;
      }
    },
    {
       "targets":[4,5,6], 
       "orderable": false,
      
    },
     
  ],
         columns: [
          { data: 'id' },
          { data: 'id' },  
          { data: 'name' },
          { data: 'status' },
          { data: 'last_week' },
          { data: 'last_month' },
          { data: 'repeat_count' },
           
         ]
      }); 
</script>

<script type="text/javascript">
 
    var id = '12';
    $fmcg('#btnsearch').click(function(){
    dataTable.draw();
    });
    $fmcg("#search_key").keydown(function (event) { 
     if (event.which == 13) { 
         event.preventDefault();
         dataTable.draw();
     }
    });
   var dataTable = $fmcg('#dataTableCategory').DataTable({
          "processing": true,
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
         serverSide: true,
         'searching': false,
         "lengthChange": false,
         "order": [ 0,'desc'],
         'ajax': {
          'url':"{{ url('getbusinessCategories') }}",

          'data': function(data){
          _token="{{csrf_token()}}";
          
           
        },
      
    }, 
   
    "columnDefs":[
      {
       "targets":0, 
       "orderable": true,
       "visible":false
      },
      {
       "targets":1, 
       "orderable": true,
       "render": function(data,type,full,meta)
      {
         return meta.row + meta.settings._iDisplayStart + 1;
      }
    },
    {
       "targets":[3,4,5,6], 
       "orderable": false,
      
    },
     
  ],
         columns: [
          { data: 'id' },
          { data: 'id' },  
          { data: 'name' },
          { data: 'status' },
          { data: 'last_week' },
          { data: 'last_month' },
          { data: 'repeat_count' },
           
         ]
      });  
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



/* bar chart */
var chBar_new = document.getElementById("chBar_new");

var day_name = new Array();
    <?php foreach($dates_name as $key => $val){ ?>
        day_name.push('<?php echo $val; ?>');
    <?php } ?>

var new_userdata = new Array();
    <?php foreach($new_userdata as $key => $val){ ?>
        new_userdata.push('<?php echo $val; ?>');
    <?php } ?>

var repeated_userdata = new Array();
    <?php foreach($repeated_userdata as $key => $val){ ?>
        repeated_userdata.push('<?php echo $val; ?>');
    <?php } ?>
         

if (chBar_new) {
  new Chart(chBar_new, {
  type: 'bar',
  data: {
    labels:day_name,
    datasets: [
    {
      data: new_userdata,
      backgroundColor: colors[0]
    },
    {
      data:repeated_userdata,
      backgroundColor: colors[1]
    }
    ]
  },
  options: {
    legend: {
      display: false
    },
    scales: {
         yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }],
      xAxes: [{
        barPercentage: 0.4,
        categoryPercentage: 0.5
      }]
    }
  }
  });
}


/* bar chart Country*/
var chBar_country = document.getElementById("chBar_country");

var countries = new Array();
var country_count = new Array();
    <?php foreach($country_count as $key => $val){ ?>
        countries.push('<?php echo $val['country']; ?>');
		country_count.push('<?php echo $val['c_count']; ?>');
    <?php } ?>


         

if (chBar_country) {
  new Chart(chBar_country, {
  type: 'bar',
  data: {
    labels:countries,
    datasets: [
    {
      data: country_count,
      backgroundColor: colors[0]
    },
    
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    legend: {
      display: false
    },
    scales: {
         yAxes: [{
                ticks: {
                    beginAtZero: true,
					stepSize: 1,
                }
            }],
      xAxes: [{
        barPercentage: 0.4,
        categoryPercentage: 0.5
      }]
    }
  }
  });
}
   </script>

   @endsection