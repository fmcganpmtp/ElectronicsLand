@extends('admin.master')
@section('title', 'Users List')
@section('breadcrumb') Admin Users @endsection
@section('content')

<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
     
        <h2>Admin users</h2>
        @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header">
                  <div class="row">
                      <div class="col-lg-4 col-12">
                      <label> Keyword search:</label>
                      <input type="search" id="search_key"  placeholder="Search...." class="form-control" value={{$search_key}}>
                     
                     </div>
                      <div class="col-lg-3 col-12">
                     <div class="form-group">
                      <label>Role Name:</label>
                      <select type="text" id="role_id"  class="form-control" >
                            <option value="">None</option>
                                @foreach($role as $item)
                                 <option value="{{$item->id}}" >{{$item->role_name}}</option>
                                @endforeach 
                     </select>
                    </div>
                     </div>

                    <div class="col-lg-2 col-12">
                     <button type="button" id="btnsearch" value="search" class="bl-btn flt-right top-mrg"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                     </div>
                      <div class="col-lg-2 col-12">
                     <a href="{{route('create.user')}}" class="bl-btn flt-right top-mrg">Create Users</a>
                     </div>
                    
                    </div>
                    
                </div>
                

                <div class="card-body">
                  <div class="tableC">
                
                  <table id="datatable" class="table  table-bordered" data-page-length='20' cellspacing="0" width="100%">
                  <thead>
                        <tr>
                          <th></th>
                          <th>Sl No</th>
                          <th>Profile Pic</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th>Role</th>
                          <th>Created Date</th>
                          <th> Actions</th>
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

 <!-- jQuery CDN -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->

<!-- Datatables JS CDN -->
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="{{asset('/admin1/js/sweetalert.js')}}"></script>
<script>
var $ = jQuery;
  (function($) {
  $(document).ready( function () {

    var id = '12';
	  var editurl="{{route('edit.user', ':id')}}";
  

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
         "lengthChange": false,
         'searching': false,
         "order": [ 0,'desc'],
         'ajax': {
       'url':"{{ url('getuserslist') }}",
       'data': function(data){        
          _token="{{csrf_token()}}";
           data.search_key = $("#search_key").val();
           data.role_id =$("#role_id").val(); 
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
       "orderable": false,
       "render": function(data,type,full,meta)
      {
         return meta.row + meta.settings._iDisplayStart + 1;

      }
    },
    {
       "targets":8, 
       "orderable": false,
       "data":"id",
       "render": function(data,type,full,meta)
      {
       editurl = editurl.replace(':id', '');
        return '<div class="icon-bx"> <a href="'+editurl+data+'"><i class="icon  cil-pencil"></i></a>  <a href="JavaScript:void(0);" onclick="deleteconfirm('+data+')"><i class="icon cil-trash"></i></a> </div>';
      }
    },
    {
      
       "targets":2, 
       "orderable": false,
       "data":"profile_pic",
       "render": function(data,type,full,meta)
      {
        return '<div class="table-prof"><img style=" width:60px !important;" class="pr_img" src="'+data+'"></div>';
      }
    }
   
  ],
         columns: [
          { data: 'id' },
          { data: 'id' },
          { data: 'profile_pic' },
          { data: 'name' },
          { data: 'email' } ,  
          { data: 'phone' },
          { data: 'role_name' },
          { data: 'created_at' }
         ]
      });

     
     
  });
})(jQuery);

function deleteconfirm(data){
  var id=data;
  var deleteurl="{{route('delete.user', ':id')}}".replace(':id', '')+data;

  swal({
    title:"Do you really want to delete? " ,
    text: "",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      window.location=deleteurl;
    } 
    else {
          
        }
    });  

}
</script>
<link href="{{asset('/admin1/css/style_dataTable.css')}}" rel="stylesheet">
@endsection

