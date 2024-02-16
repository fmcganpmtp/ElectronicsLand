@extends('admin.master')
@section('title', 'List Packages')
@section('breadcrumb') List Packages @endsection
@section('content')
<style>
  table tr td:nth-child(7), table tr th:nth-child(7) {
    text-align:center !important;
}
table tr td:nth-child(5) {
    text-align:right !important;
}
  </style>
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
      @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
        <h2>Subscription Packages</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header">
                <div class="row">

                    <div class="col-lg-4 col-12">
                    <!--<label> Package Type:</label>
                    <select id="Package" class="form-control">
                      <option value="">All</option>
                      <option value="Extended">Extended</option>
                      <option value="Standard">Standard</option>
                    </select>
-->
                    <input type="text" name="search_key" id="search_key"  placeholder="Search...." class="form-control">
                    </div>

<div class="col-lg-4 col-12">
<div class="form-group">
 <!--<label>User Type:</label> 
          <select id="user_type" class="form-control">
                      <option value="">All</option>
                      <option value="Seller">Seller</option>
                      <option value="Buyer">Buyer</option>
          </select>-->
</div>
</div>


<div class="col-lg-2 col-12">
<button type="button" id="btnsearch" value="search" class="bl-btn flt-right top-mrg"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
</div>
<div class="col-lg-2 col-12">
<a href="{{route('create.package')}}" class="bl-btn flt-right top-mrg"> <i class="fa fa-pencil" aria-hidden="true"></i> Create</a>   
</div>
</div>


              </div>
                

                <div class="card-body">
                  <div class="tableC">
                    
                    <table id="datatable" class="table  table-bordered" data-page-length='20' cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Sl no</th>
                          <th>Name</th>
                         <!-- <th> Type</th>
                          <th>User type</th>-->
                          <th>Price</th>
                          <th>Validity</th>
                        <!--  <th>No of subscriptions</th>-->
                          <th style=" text-align: center; " >Actions</th>
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
var editurl="{{route('edit.package', ':id')}}";
var viewurl="{{route('view.package', ':id')}}";

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
       'url':"{{ url('getpackagelist') }}",
       'data': function(data){
          _token="{{csrf_token()}}";
          data.Package = $("#Package").val(); 
          data.user_type = $("#user_type").val();
          data.search_key = $("#search_key").val();
          
       },
      
    }, 
   
    "columnDefs":[
    {
       "targets":0, 
       "orderable": true,
       "visible":false
      },
      {
       "targets":[1], 
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
        viewurl = viewurl.replace(':id', '');
        return '<div class="icon-bx"> <a href="'+viewurl+data+'"><i class="fa fa-eye" aria-hidden="true"></i></a> <a href="'+editurl+data+'"><i class="icon  cil-pencil"></i></a> <a href="JavaScript:void(0);" onclick="deleteconfirm('+data+')"><i class="icon cil-trash"></i></a> </div>';
 
      }
    }
  ],
         columns: [
         { data: 'id' },
          { data: 'id' },
          { data: 'name' },
         // { data: 'subscription_type' } ,  
        //  { data: 'user_type' },
          { data: 'package_basic_price' },
          { data: 'package_validity' },
        //  { data: 'cnt' },
         
         ]
      });

    
     
  });
})(jQuery);

function deleteconfirm(data){
  var id=data;
  var deleteurl="{{route('delete.package', ':id')}}".replace(':id', '')+data;

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

