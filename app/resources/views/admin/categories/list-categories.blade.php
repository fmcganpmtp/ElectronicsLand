@extends('admin.master')
@section('title', 'List Categories')
@section('breadcrumb') Categories @endsection
@section('content')
   
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


<link rel="stylesheet" href="https://www.hermosoftech.com/projects/designs/fmcg/admin/css/style.css">

 
    <div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
      @if(Session::has('success')) <div class="alert alert-success">{{Session::get('success') }}</div> @endif
        <h2>Categories</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header">

                  <div class="row">
                    <div class="col-lg-6 col-12">
                    <input type="search" id="search_key"  placeholder="Search...." class="form-control" value={{$search_key}}>
                    </div>
<div class="col-lg-3 col-12">
<button type="button" id="btnsearch" value="search" class="bl-btn flt-right"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
</div>
<div class="col-lg-3 col-12">
 <a href="{{ route('createCategory')}}" class="bl-btn flt-right">Create category</a></div>
</div>
                
                    
                   
                  </div>
                <div class="card-body">
                  <div class="tableC">
                 
                    <table id="datatable" class="table  table-bordered" data-page-length='20' cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th></th>

                         <th >Sl no</th>
                          <th>Category</th>
                           <th>Icon</th>
                          <th> Parent Category</th>            
                          <th > Actions</th>
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
   
    var viewurl="{{route('view.category', ':id')}}";
    var editurl="{{route('edit.category', ':id')}}";

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
       'url':"{{ url('getcategorylist') }}",
       'data': function(data){
          _token="{{csrf_token()}}";
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
      },{
       "targets":4, 
       "orderable": false,
       "data":"parent",
       "render": function(data,type,full,meta)
      {
        return data;
      }
    },{
       "targets":3, 
       "orderable": false,
       "data":"pic",
       "render": function(data,type,full,meta)
      {
        return '<div class="table-prof"><img style=" width:60px !important;" class="pr_img" src="'+data+'"></div>';
      }
    }
     ,
      {
       "targets":5, 
       "orderable": false,
       "data":"id",
       "render": function(data,type,full,meta)
      {
        viewurl = viewurl.replace(':id', '');
        editurl = editurl.replace(':id', '');
        return '<div class="icon-bx"><a href="'+viewurl+data+'"><i class="fa fa-eye" aria-hidden="true"></i></a><a href="'+editurl+data+'"><i class="icon  cil-pencil"></i></a> <a href="JavaScript:void(0);" onclick="deleteconfirm('+data+')"><i class="icon cil-trash"></i></a> </div>';
      }

    }
  ],
         columns: [
         { data: 'id' },
          { data: 'id' },
          { data: 'name' },
          { data: 'pic' } ,  
          { data: 'parent' },
         
         ]
      });

    
     
  });
})(jQuery);

function deleteconfirm(data){
  var id=data;
  var deleteurl="{{route('delete.category', ':id')}}".replace(':id', '')+data;

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