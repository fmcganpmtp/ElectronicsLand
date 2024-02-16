@extends('admin.master')
@section('title', 'Newsletter Subscribers')
@section('breadcrumb') Newsletter Subscribers @endsection
@section('content')
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
    @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
      <div class="card-out mb-4 inner-form">
        <h2>Newsletter Subscribers</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
		
              <div class="card ">
                <div class="card-header">
                <form action="{{route('admin.newsletters')}}" method="GET">  
                <div class="row">

                <div class="col-lg-4 col-12">
                <div class="form-group">
                 <label>User Types:</label> 
                                    <select name="user_types" id="user_types" class="form-control">
                                        <option value=""> All</option>
                                        <option value="guests"> Guests</option>
                                        <option value="registered_users">Registered Users</option>
                                      </select>
                </div>
                </div>
                 <div class="col-lg-4 col-12"></div>

                <div class="col-lg-2 col-12">
                  <button type="submit" name="filter" value="Excel" class="bl-btn flt-right1 top-mrg pd01"> <i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel export</button> 
                </div>

                <div class="col-lg-2 col-12">
                   <button type="button" id="btnsearch" value="search" class="bl-btn flt-right top-mrg"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                </div>
                
                </div>
              </form>
              </div>


              <div class="col-lg-12 col-12">
                <div class="card-body testi-list">
                  <div class="tableC">
                  <table id="datatable" class="table  table-bordered" data-page-length='20' cellspacing="0" width="100%">
                     <thead>
                        <tr>
                          <th></th>
                          <th>Sl no</th>
                          <th>Name</th>
                          <th>Surname</th>
                          <th>Company</th>
                          
                          <th>Email</th>
                          <!-- <th>User Type</th> -->
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
  </div>
<script src="{{asset('/admin1/js/datatable.js')}}"></script>
<script>
var $ = jQuery;
  (function($) {
  $(document).ready( function () {
  
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
        'url':"{{ url('getnewsletterslist') }}",
        'data': function(data){
          _token="{{csrf_token()}}";
         data.user_types= $("#user_types").val();
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
          { data: 'surname' }, 
          { data: 'company_name' }, 
          
          { data: 'email' },
         ]
      });

  
     
  });
})(jQuery);

</script>
@endsection