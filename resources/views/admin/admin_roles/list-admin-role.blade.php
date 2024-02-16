@extends('admin.master')
@section('title', 'List Admin Roles')
@section('breadcrumb') Admin Roles @endsection
@section('content')




 
  <div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
      @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
        <h2>Admin Roles</h2>
       
        <div class="card-body">
          <div class="row">
            <div class="col-lg-9 col-12">
              <div class="card ">
                <div class="card-header">
                <div class="search-area wth-btn">
                    
                    <div class="form-group">
                     <!-- <input type="email" class="form-control" value="Search..">
                     <button type="submit"><i class="icon cil-search"></i></button> -->
                   </div>
                   
                   </div> <a href="{{ route('create.admin.role')}}" class="bl-btn flt-right">Create Role</a>
              </div>
             
                <div class="card-body">
                  <div class="tableC ad-rl-list">
                  <table id="datatable" class="table  table-bordered" data-page-length='20' cellspacing="0" width="100%">
                    <thead>
                        <tr>
                          <th></th>
                          <th>Sl No</th>
                          <th>Role Name</th>
                          <th>No of Users</th>
                          <th>Created Date</th>
                          <th> Actions</th>
                        </tr>
                      </thead>
                      
                    </table>
                  </div>
                
                  @include('admin.UserButtons')
                  
                  
                  <!--<div class="right-paging">
                    <nav aria-label="Page navigation example">
                      <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                      </ul>
                    </nav>
                  </div>-->
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
	  var editurl="{{route('edit.admin.role', ':id')}}";


    $('#datatable').DataTable({
        "processing": true,
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
         serverSide: true,
        'searching': false,
         "lengthChange": false,
          "order": [ 0,'desc'],
         'ajax': {
       'url':"{{ url('getrolevalues') }}",
       'data': function(){
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
       "orderable": false,
       "render": function(data,type,full,meta)
      {
        return meta.row + meta.settings._iDisplayStart + 1;

      }
    },
     
      {
       "targets":5, 
       "orderable": false,
       "data":"id",
       "render": function(data,type,full,meta)
      {
        editurl = editurl.replace(':id', '');
        return "<div class='icon-bx'> <a href='"+editurl+data+"'><i class='icon  cil-pencil'></i></a>  <a href='JavaScript:void(0);' onclick='deleteconfirm("+data+")'><i class='icon cil-trash'></i></a> </div>";
      }
    }
  ],
         columns: [
          { data: 'id' },
          { data: 'id' },
          { data: 'role_name' },
          { data: 'cnt' } ,  
          { data: 'created_at' }    
         ]
      });

    
     
  });
})(jQuery);

function deleteconfirm(data){
  var id=data;
  var deleteurl="{{route('delete.admin.role', ':id')}}".replace(':id', '')+data;

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

@endsection