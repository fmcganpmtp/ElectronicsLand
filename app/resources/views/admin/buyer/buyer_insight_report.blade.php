@extends('admin.master')
@section('title', 'Insight Report')
@section('breadcrumb')Insight Report @endsection
@section('content')

  <div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
      @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
        <h2>Buyers Insight Report</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header">
                 <div class="row">
                            <div class="col-lg-4 col-12">
                    
                    <div class="search-area wth-btn width_100">
                    
                     <div class="form-group">
                     <input type="search" name="search_key" id="search_key"  placeholder="Search...." class="form-control">
                   </div>
                    
                    </div>
                     </div>
                    
                    
                        <div class="col-lg-6 col-12">
                        <button type="button" class="bl-btn pd01 " id="btnsearch"><i class="fa fa-search" aria-hidden="true"></i> Search</button> 
                      </div>
                    </div>
                </div>
              
                <div class="card-body">
                  <div class="tableC ad-rl-list">
                  <table id="datatable" class="table  table-bordered" data-page-length='20' cellspacing="0" width="100%">
                    <thead>
                        <tr>
                          <th></th>
                          <th>Sl no</th>
                          <th>Name</th>
                          <th>Company Name</th>
                          <th>Logins</th>
                          <th>Product Views</th>
                          <th>Message Sent</th>
                          <th>Message Received</th>
                          <th>Network</th>
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
<script src="{{asset('/admin1/js/sweetalert.js')}}"></script>
<script>
var $ = jQuery;
  (function($) {
  $(document).ready( function () { 
    var id = '12';
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
        'url':"{{ url('getbuyersinsightlist') }}",
        'data': function(data){
          _token="{{csrf_token()}}";
          data.search_key = $("#search_key").val();
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
    },
  ],
         columns: [
          { data: 'id' },
          { data: 'id' },
          { data: 'name' },
          { data: 'company_name' } ,  
          { data: 'login_cnt' } ,
          { data: 'product_view' } ,
          { data: 'mesage_sent' },  
          { data: 'message_received' },
          { data: 'network_cnt' }, 
         ]
      });

  
     
  });
})(jQuery);
</script>

@endsection