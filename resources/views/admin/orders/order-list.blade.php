@extends('admin.master')
@section('title', 'Orders History')
@section('breadcrumb') Orders History @endsection
@section('content')


<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Orders History</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                
                <div class="card-header">
                	
                    
                
                    
                    <div class="row">
                            <div class="col-lg-4 col-12">
                    
                    <div class="search-area wth-btn width_100">
                    
                     <div class="form-group">
                      <input type="email" id="search_key" class="form-control" placeholder="Keyword search..">
                    </div>
                    
                    </div>
                     </div>
                    
                    <div class="col-lg-2 col-12">
                    
                    
                    
                        	<div class="form-group">

                          <select type="text" id="package"  class="form-control" >
                                        <option value="">All</option>
                                        @foreach($package as $item)
                                            <option value="{{$item->id}}" >{{$item->name}}</option>
                                        @endforeach
                                       </select>
                    </div>
                        </div>
                    
                    	
                        
                        
                        <div class="col-lg-6 col-12">
                        	  <button type="button" id="btnsearch" class="bl-btn pd01 "><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                              
                        </div>               
                    </div>
                </div>               
              
                <div class="card-body">
                  <div class="tableC  pr-list">
                  <table id="datatable" class="table  table-bordered" data-page-length='20' cellspacing="0" width="100%">
                   <thead>
                        <tr>
                          <th></th>
                          <th>Sl no</th>
                          <th>Name</th>
                          <th>Type</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th>Address</th>    
                          <th>Completed</th>
                          <th>Order Date</th>
                          <th>Package</th>
                          <!-- <th>Type</th> -->
                         <th>Price</th>
                         <th>Validity</th>
                         <th>Expiry</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="{{asset('/admin1/js/datatable.js')}}"></script>
<script>
var $ = jQuery;
  (function($) {
  $(document).ready( function () {
    var id = '12';
	var editurl="{{route('admin.sellerview', ':id')}}";
    var Blocked="'Blocked'";
    var Active="'Active'";
    $('#btnsearch').click(function(){
    dataTable.draw();
    });
    $("#search_key").keydown(function (event) { 
     if (event.which == 13) { 
         event.preventDefault();
         dataTable.draw();
     }
    });
    var dataTable=$('#datatable').DataTable({
         "processing": true,
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
         serverSide: true,
        'searching': false,
        "lengthChange": false,
        "order": [ 0,'desc'],
        'ajax': {
        'url':"{{ url('getorderslist') }}",
        'data': function(data){
          _token="{{csrf_token()}}";
          data.search_key = $("#search_key").val();
          data.package= $("#package").val();
       },
      
    },"columnDefs":[
    {
       "targets":0, 
       "orderable": true,
       "visible":false
      },
      {
       "targets":1, 
       "orderable": false,
       "render": function(data,type,full,meta)
      {
        return meta.row + meta.settings._iDisplayStart + 1;
      }
    }
  ],

         columns: [
          { data: 'id' },
          { data: 'id' },
          { data: 'users_name' },
          { data: 'user_type' },    
          { data: 'email' },
          { data: 'phone' },
          { data: 'address' },
          { data: 'anyval' },
          { data: 'created_date' },       
          { data: 'package_name' } ,   
          // { data: 'subscription_type' } ,
          { data: 'package_basic_price' },  
          { data: 'package_validity' },  
          { data: 'expairy_date' }
        
         ]
      });

  
     
  });
})(jQuery);

 //<a href="'+editurl+full.id+'"><i class="fa fa-eye" aria-hidden="true"></i></a> 

function fnstatusupdator(id,type){
  if(confirm("Are you Sure?"))  
  {
       $(".loaderajax").show();
       $.ajax({
         url: "{{ url('sellersstatusupdates') }}",
            type: "post",
            data:{ 
                _token:'{{ csrf_token() }}',
                  id: id,
                  status: type,
            },
            async:true,
            cache: false,
            dataType: 'json',
            success: function(data){
             $(".loaderajax").hide();        
             swal(data, "", "success"); 
              location.reload();
          } ,
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
               $(".loaderajax").hide();
            alert("Error: " + errorThrown); 
          }  

        })  ;
    
  }
}

</script>
@endsection